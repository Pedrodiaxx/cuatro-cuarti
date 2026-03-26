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
use Illuminate\Support\Str;

class ImportPatientsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $extension;

    /**
     * Create a new job instance.
     */
    public function __construct($filePath, $extension = 'csv')
    {
        $this->filePath = $filePath;
        $this->extension = strtolower($extension);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $file = Storage::path($this->filePath);
        
        if (!file_exists($file)) return;

        $rows = [];
        
        if (in_array($this->extension, ['xls', 'xlsx'])) {
            $rows = $this->parseXlsx($file);
        } else {
            // Parse CSV natively
            $content = file_get_contents($file);
            // Limpiar BOM characters
            $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
            file_put_contents($file, $content);

            $handle = fopen($file, 'r');
            $header = fgetcsv($handle, 1000, ',');
            
            // Determinar delimitador
            $delimiter = ',';
            if($header && count($header) == 1 && strpos($header[0], ';') !== false) {
                rewind($handle);
                $header = fgetcsv($handle, 1000, ';');
                $delimiter = ';';
            }

            if ($header) {
                $rows[] = $header; // include header as first row for consistency
                while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                    $rows[] = $row;
                }
            }
            fclose($handle);
        }

        if (count($rows) > 1) {
            $header = array_shift($rows);
            // Trim y lower a los headers para asegurar la consistencia
            $header = array_map(function($h) { return strtolower(trim((string)$h)); }, $header);
            
            foreach ($rows as $row) {
                if (count($header) !== count($row)) {
                    $row = array_pad($row, count($header), null);
                    $row = array_slice($row, 0, count($header));
                }
                
                $data = array_combine($header, $row);
                
                // Nombres de columna permisivos
                $email = $data['email'] ?? $data['correo'] ?? null;
                $name = $data['name'] ?? $data['nombre'] ?? null;

                if (empty($email) || empty($name)) {
                    continue;
                }

                $idNumber = $data['id_number'] ?? $data['cedula'] ?? (string) Str::uuid();

                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => $name,
                        'password' => Hash::make($idNumber),
                        'id_number' => $idNumber,
                        'phone' => $data['phone'] ?? $data['telefono'] ?? '0000000000',
                        'address' => $data['address'] ?? $data['direccion'] ?? 'Sin dirección',
                    ]
                );

                $btName = $data['blood_type'] ?? $data['tipo_sangre'] ?? $data['blood_type_id'] ?? null;
                $bloodTypeId = null;
                if (!empty($btName)) {
                    $bloodType = BloodType::firstOrCreate(['name' => strtoupper($btName)]);
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
        }
        
        // Delete the file after processing
        if (Storage::exists($this->filePath)) {
            Storage::delete($this->filePath);
        }
    }

    /**
     * Natively parse .xlsx logic with ZipArchive
     */
    protected function parseXlsx($filePath)
    {
        $rows = [];
        $zip = new \ZipArchive;
        if ($zip->open($filePath) === true) {
            $sharedStrings = [];
            if (($index = $zip->locateName('xl/sharedStrings.xml')) !== false) {
                $xmlString = $zip->getFromIndex($index);
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
            
            // Locate the first worksheet
            $sheetPath = 'xl/worksheets/sheet1.xml';
            if (($index = $zip->locateName($sheetPath)) !== false) {
                $xmlString = $zip->getFromIndex($index);
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
                                    $idx--; // zero based column index
                                    
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
            $zip->close();
        }
        return $rows;
    }
}
