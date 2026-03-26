<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Patient;
use App\Models\BloodType;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Support\Str;

class PatientsImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Require at least email and name
        if (empty($row['email']) || empty($row['name'])) {
            return null;
        }

        // Find or create the User
        // Use ID number or generated ID if empty
        $idNumber = $row['id_number'] ?? (string) Str::uuid();

        $user = User::firstOrCreate(
            ['email' => $row['email']],
            [
                'name' => $row['name'],
                'password' => Hash::make($idNumber), // default password
                'id_number' => $idNumber,
                'phone' => $row['phone'] ?? '0000000000',
                'address' => $row['address'] ?? 'Sin dirección',
            ]
        );

        // Find or create BloodType if provided
        $bloodTypeId = null;
        if (!empty($row['blood_type'])) {
            $bloodType = BloodType::firstOrCreate(['name' => strtoupper($row['blood_type'])]);
            $bloodTypeId = $bloodType->id;
        }

        // Create the Patient if user doesn't have one
        if (!$user->patient) {
            return new Patient([
                'user_id' => $user->id,
                'blood_type_id' => $bloodTypeId,
                'allergies' => $row['allergies'] ?? null,
                'chronic_conditions' => $row['chronic_conditions'] ?? null,
                'surgical_history' => $row['surgical_history'] ?? null,
                'family_history' => $row['family_history'] ?? null,
                'observations' => $row['observations'] ?? null,
                'emergency_contact_name' => $row['emergency_contact_name'] ?? null,
                'emergency_contact_phone' => $row['emergency_contact_phone'] ?? null,
                'emergency_contact_relationship' => $row['emergency_contact_relationship'] ?? null,
            ]);
        }

        return null;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
