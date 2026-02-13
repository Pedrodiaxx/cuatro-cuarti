<?php

namespace App\Http\Controllers\Admin;

use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use App\Models\BloodType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.patients.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.patients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        return view('admin.patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        $bloodTypes = BloodType::all();
        return view('admin.patients.edit', compact('patient', 'bloodTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $data= $request->validate([
            'blood_type_id' => 'nullable|exists:blood_types,id',
            'allergies' => 'nullable|string|min:3|max:250',
            'chronic_conditions' => 'nullable|string|min:3|max:255',
            'surgical_history' => 'nullable|string|min:3|max:255',
            'family_history' => 'nullable|string|min:3|max:255',
            'observations' => 'nullable|string|min:3|max:250',
            'emergency_contact_name' => 'nullable|string|min:3|max:255',
            'emergency_contact_phone' => ['nullable', 'string', 'min:10', 'max:12'],
            'emergency_contact_relationship' => 'nullable|string|min:3|max:50',
            ]);
        
        $patient->update($data);

        session()->flash('swal',
        [
            'icon' => 'success',
            'title' => '¡Paciente actualizado!',
            'text' => 'Los datos del paciente se han actualizado correctamente.',
        ]);
        return redirect()->route('admin.patients.edit', $patient)->with('success', 'Paciente actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        //
    }
}
