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

    /**
     * Create a new job instance.
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $file = Storage::path($this->filePath);
        
        if (!file_exists($file)) return;

        // Limpiar BOM characters si existen (algunos Excels guardan CSV con BOM)
        $content = file_get_contents($file);
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
        file_put_contents($file, $content);

        $handle = fopen($file, 'r');
        $header = fgetcsv($handle, 1000, ',');
        
        // Determinar delimitador (coma o punto y coma)
        $delimiter = ',';
        if($header && count($header) == 1 && strpos($header[0], ';') !== false) {
            rewind($handle);
            $header = fgetcsv($handle, 1000, ';');
            $delimiter = ';';
        }

        if ($header) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                // Alinear los headers y row para evitar error en array_combine
                if (count($header) !== count($row)) {
                    $row = array_pad($row, count($header), null);
                    $row = array_slice($row, 0, count($header));
                }
                
                $data = array_combine($header, $row);
                
                if (empty($data['email']) || empty($data['name'])) {
                    continue; // Requisito mínimo
                }

                $idNumber = $data['id_number'] ?? (string) Str::uuid();

                $user = User::firstOrCreate(
                    ['email' => $data['email']],
                    [
                        'name' => $data['name'],
                        'password' => Hash::make($idNumber),
                        'id_number' => $idNumber,
                        'phone' => $data['phone'] ?? '0000000000',
                        'address' => $data['address'] ?? 'Sin dirección',
                    ]
                );

                $bloodTypeId = null;
                if (!empty($data['blood_type'])) {
                    $bloodType = BloodType::firstOrCreate(['name' => strtoupper($data['blood_type'])]);
                    $bloodTypeId = $bloodType->id;
                }

                if (!$user->patient) {
                    Patient::create([
                        'user_id' => $user->id,
                        'blood_type_id' => $bloodTypeId,
                        'allergies' => $data['allergies'] ?? null,
                        'chronic_conditions' => $data['chronic_conditions'] ?? null,
                        'surgical_history' => $data['surgical_history'] ?? null,
                        'family_history' => $data['family_history'] ?? null,
                        'observations' => $data['observations'] ?? null,
                        'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
                        'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
                        'emergency_contact_relationship' => $data['emergency_contact_relationship'] ?? null,
                    ]);
                }
            }
        }
        
        fclose($handle);
        
        // Delete the file after processing
        if (Storage::exists($this->filePath)) {
            Storage::delete($this->filePath);
        }
    }
}
