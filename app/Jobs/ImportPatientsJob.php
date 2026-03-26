<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Patient;
use App\Models\BloodType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ImportPatientsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $extension;
    protected $importId;

    public function __construct($filePath, $extension = 'csv', $importId = '')
    {
        $this->filePath = $filePath;
        $this->extension = strtolower($extension);
        $this->importId = $importId;
    }

    public function handle(): void
    {
        $file = Storage::path($this->filePath);
        
        if (!file_exists($file)) {
            if ($this->importId) Cache::put("import_{$this->importId}", ['progress' => 100, 'status' => 'error'], 3600);
            return;
        }

        $rows = [];
        
        if (in_array($this->extension, ['xls', 'xlsx'])) {
            $rows = $this->parseXlsx($file);
        } else {
            $content = file_get_contents($file);
            $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
            file_put_contents($file, $content);

            $handle = fopen($file, 'r');
            $header = fgetcsv($handle, 1000, ',');
            
            $delimiter = ',';
            if($header && count($header) == 1 && strpos($header[0], ';') !== false) {
                rewind($handle);
                $header = fgetcsv($handle, 1000, ';');
                $delimiter = ';';
            }

            if ($header) {
                $rows[] = $header; 
                while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                    $rows[] = $row;
                }
            }
            fclose($handle);
        }

        $totalRows = count($rows) - 1; 

        if ($totalRows > 0) {
            $header = array_shift($rows);
            $header = array_map(function($h) { return strtolower(trim((string)$h)); }, $header);
            
            $processed = 0;

            foreach ($rows as $row) {
                if (count($header) !== count($row)) {
                    $row = array_pad($row, count($header), null);
                    $row = array_slice($row, 0, count($header));
                }
                
                $data = array_combine($header, $row);
                // Nombres de columna permisivos y exactos basados en el repo comprobado
                $email = $data['email'] ?? $data['correo'] ?? $data['correo_electronico'] ?? $data['e-mail'] ?? null;
                $name = $data['nombre_completo'] ?? $data['name'] ?? $data['nombre'] ?? $data['nombres'] ?? $data['paciente'] ?? 'Sin Nombre';

                if (!empty($email)) {

                    $idNumber = $data['id_number'] ?? $data['numero_de_id'] ?? $data['cedula'] ?? ('CSV-' . uniqid());

                    $user = User::firstOrCreate(
                        ['email' => $email],
                        [
                            'name' => $name,
                            'password' => Hash::make($idNumber),
                            'id_number' => $idNumber,
                            'phone' => $data['telefono'] ?? $data['phone'] ?? '0000000000',
                            'address' => $data['direccion'] ?? $data['address'] ?? 'No registrada',
                        ]
                    );

                    // Requisito detectado: Asignar rol de paciente si existe
                    if (method_exists($user, 'assignRole')) {
                        try {
                            $user->assignRole('paciente');
                        } catch (\Throwable $th) {}
                    }

                    $btName = $data['tipo_sangre'] ?? $data['blood_type'] ?? $data['blood_type_id'] ?? null;
                    $bloodTypeId = null;
                    if (!empty($btName)) {
                        $bloodType = BloodType::firstOrCreate(['name' => strtoupper(trim($btName))]);
                        $bloodTypeId = $bloodType->id;
                    }

                    if (!$user->patient) {
                        Patient::create([
                            'user_id' => $user->id,
                            'blood_type_id' => $bloodTypeId,
                            'allergies' => $data['allergies'] ?? $data['alergias'] ?? null,
                            'chronic_conditions' => $data['chronic_conditions'] ?? $data['condiciones_cronicas'] ?? null,
                            'surgical_history' => $data['surgical_history'] ?? $data['historial_quirurgico'] ?? null,
                            'family_history' => $data['family_history'] ?? $data['antecedentes_familiares'] ?? null,
                            'observations' => $data['observations'] ?? $data['observaciones'] ?? null,
                            'emergency_contact_name' => $data['emergency_contact_name'] ?? $data['contacto_emergencia_nombre'] ?? null,
                            'emergency_contact_phone' => $data['emergency_contact_phone'] ?? $data['contacto_emergencia_telefono'] ?? null,
                            'emergency_contact_relationship' => $data['emergency_contact_relationship'] ?? $data['contacto_emergencia_relacion'] ?? null,
                        ]);
                    }
                }

                $processed++;
                if ($this->importId && ($processed % 5 === 0 || $processed === $totalRows)) {
                    $progress = min(100, round(($processed / $totalRows) * 100));
                    Cache::put("import_{$this->importId}", ['progress' => $progress, 'status' => 'running'], 3600);
                }
            }
        }
        
        if ($this->importId) {
            Cache::put("import_{$this->importId}", ['progress' => 100, 'status' => 'completed'], 3600);
        }

        if (Storage::exists($this->filePath)) {
            Storage::delete($this->filePath);
        }
    }

    protected function parseXlsx($filePath)
    {
        $rows = [];
        $extractDir = storage_path('app/imports/temp_' . uniqid());
        // FALLBACK MAGISTRAL: Usar powershell incondicionalmente para evitar el fantasma de ZipArchive
        $extractDirStr = storage_path('app/imports/temp_' . uniqid());
        $extractDir = realpath(storage_path('app/imports')) . DIRECTORY_SEPARATOR . basename($extractDirStr);
        $psCmd = 'powershell.exe -NoProfile -NonInteractive -Command "Expand-Archive -Path \'' . realpath($filePath) . '\' -DestinationPath \'' . $extractDir . '\' -Force"';
        exec($psCmd);
        
        $sharedStringsPath = $extractDir . '/xl/sharedStrings.xml';
        $sheetPath = $extractDir . '/xl/worksheets/sheet1.xml';

        if (file_exists($sheetPath)) {
            $sharedStrings = [];
            
            if (file_exists($sharedStringsPath)) {
                $xmlString = file_get_contents($sharedStringsPath);
                $xml = @simplexml_load_string($xmlString);
                if ($xml && isset($xml->si)) {
                    foreach ($xml->si as $val) {
                        if (isset($val->t)) {
                            $sharedStrings[] = (string)$val->t;
                        } elseif (isset($val->r)) {
                            $res = '';
                            foreach ($val->r as $r) {
                                if (isset($r->t)) $res .= (string)$r->t;
                            }
                            $sharedStrings[] = $res;
                        } else {
                            $sharedStrings[] = '';
                        }
                    }
                }
            }
            
            $xmlString = file_get_contents($sheetPath);
            $xml = @simplexml_load_string($xmlString);
            if ($xml && isset($xml->sheetData->row)) {
                foreach ($xml->sheetData->row as $row) {
                    $rowData = [];
                    $colIndex = 0;
                    if (isset($row->c)) {
                        foreach ($row->c as $c) {
                            $r = (string)$c['r'];
                            preg_match('/([A-Z]+)(\d+)/', $r, $matches);
                            if (!empty($matches[1])) {
                                $letters = str_split($matches[1]);
                                $idx = 0;
                                foreach($letters as $char) {
                                    $idx = $idx * 26 + (ord($char) - 64);
                                }
                                $idx--; 
                                
                                while ($colIndex < $idx) {
                                    $rowData[] = null;
                                    $colIndex++;
                                }
                            }

                            $val = (string)$c->v;
                            if (isset($c['t']) && (string)$c['t'] == 's') {
                                $val = $sharedStrings[(int)$val] ?? '';
                            }
                            $rowData[] = $val;
                            $colIndex++;
                        }
                    }
                    $rows[] = $rowData;
                }
            }
        }

        $this->deleteDirectory($extractDir);
        return $rows;
    }

    protected function deleteDirectory($dir) {
        if (!file_exists($dir)) {
            return true;
        }
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        return rmdir($dir);
    }
}
