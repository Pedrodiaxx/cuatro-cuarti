<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $doctor = $this->route('doctor');
        $doctorId = $doctor ? $doctor->id : null;

        return [
            'user_id' => 'required|exists:users,id|unique:doctors,user_id,' . $doctorId,
            'speciality_id' => 'required|exists:specialities,id',
            'medical_license_number' => 'required|string|max:100',
            'biography' => 'nullable|string',
        ];
    }
}