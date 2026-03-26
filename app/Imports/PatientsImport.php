<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;
use Illuminate\Support\Facades\Cache;

class PatientsImport implements ToCollection, WithHeadingRow, WithChunkReading, WithEvents
{
    protected $importId;

    public function __construct(string $importId)
    {
        $this->importId = $importId;
    }

    /**
     * Antes de importar, contamos el total de filas que tiene el archivo para nuestra barra.
     */
    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function(BeforeImport $event) {
                $totalRowsArray = $event->reader->getTotalRows();
                // Sumamos si hay más de una hoja
                $totalRows = array_sum($totalRowsArray);
                
                $data = Cache::get($this->importId, ['current' => 0, 'total' => 1, 'status' => 'processing']);
                $data['total'] = $totalRows > 0 ? $totalRows : 1;
                Cache::put($this->importId, $data, 3600);
            },
        ];
    }
    /**
    * @param Collection $rows
    */
    public function collection(Collection $rows)
    {
        $data = Cache::get($this->importId, ['current' => 0, 'total' => 1, 'status' => 'processing']);

        foreach ($rows as $row) {
            // Cabeceras exactas del CSV del usuario o alternativas
            $email = $row['correo'] ?? $row['email'] ?? null;
            $name = $row['nombre_completo'] ?? $row['nombre'] ?? $row['name'] ?? 'Sin Nombre';
            $phone = $row['telefono'] ?? $row['phone'] ?? '0000000000';
            
            // El usuario no tiene NÚMERO DE ID en el excel, pero la base de datos lo exige
            $idNumber = $row['id_number'] ?? $row['numero_de_id'] ?? $row['cedula'] ?? ('CSV-' . uniqid());
            
            $address = $row['direccion'] ?? $row['address'] ?? 'No registrada';

            if (empty($email)) {
                // Saltamos solo si no tiene ni siquiera correo
                \Log::warning('Fila saltada (sin email): ' . json_encode($row->toArray()));
                continue; 
            }

            // Validar de inmediato si ya existe en la Base de Datos para lanzar el mensaje de alerta
            $exists = User::where('email', $email)->orWhere('id_number', $idNumber)->first();
            if ($exists) {
                // Detonamos un Exception para que el Worker de Laravel lo ataje, suspenda todo y lo mande al Frontend
                throw new \Exception("El paciente con correo '$email' o cédula '$idNumber' ya existe en tu base de datos.");
            }

            // Crear o actualizar usuario
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'id_number' => $idNumber,
                    'phone' => $phone,
                    'address' => $address,
                    'password' => Hash::make($idNumber),
                ]
            );

            // Intentar asignar el rol 'paciente' si existe Spatie Permission.
            if (method_exists($user, 'assignRole')) {
                try {
                    $user->assignRole('paciente');
                } catch (\Throwable $th) {
                    // Skip if role doesn't exist
                }
            }

            // Buscar el ID del tipo de sangre
            $bloodTypeId = null;
            $tipo_sangre = $row['tipo_sangre'] ?? null;
            if(!empty($tipo_sangre)) {
                $bt = \App\Models\BloodType::where('name', trim($tipo_sangre))->first();
                if($bt) {
                    $bloodTypeId = $bt->id;
                }
            }

            // Crear o actualizar paciente
            Patient::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'blood_type_id' => $bloodTypeId,
                    'allergies' => $row['alergias'] ?? $row['allergies'] ?? null,
                    'chronic_conditions' => $row['chronic_conditions'] ?? null,
                    'surgical_history' => $row['surgical_history'] ?? null,
                    'family_history' => $row['family_history'] ?? null,
                    'observations' => $row['observations'] ?? null,
                    'emergency_contact_name' => $row['emergency_contact_name'] ?? null,
                    'emergency_contact_phone' => $row['emergency_contact_phone'] ?? null,
                    'emergency_contact_relationship' => $row['emergency_contact_relationship'] ?? null,
                ]
            );
            
            $data['current']++; // Sumar progreso
        }
        
        // Guardar progreso en caché después del bloque (chunk) para no golpear Redis/DB por registro
        Cache::put($this->importId, $data, 3600);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
