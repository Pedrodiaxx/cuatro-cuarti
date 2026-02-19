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
        'user_id' => ['required', 'exists:users,id', 'unique:doctors,user_id'],
        'speciality_id' => ['nullable', 'exists:specialities,id'],
        'medical_license_number' => ['nullable', 'string', 'max:255'],
        'biography' => ['nullable', 'string'],
    ];
}
}
