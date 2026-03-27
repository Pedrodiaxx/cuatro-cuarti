<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // IMPORTANTE: si está en false, nunca guardará doctores
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
{
    return [
        'user_id' => 'required|exists:users,id|unique:doctors,user_id',
        'speciality_id' => 'required|exists:specialities,id',

        // 👇 NOMBRE CORRECTO (como tu modelo)
        'medical_license_number' => 'required|string|max:100',

        'biography' => 'nullable|string',
    ];
}
}