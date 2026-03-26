<?php

namespace App\Http\Controllers\Admin;

use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use App\Models\BloodType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\ProcessPatientsImport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\HeadingRowImport;

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
     * Handle the file upload and dispatch the job for patient import.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv,xls|max:10240', // 10MB default max
        ]);

        $path = $request->file('file')->store('imports', 'local');

        try {
            // Leer las cabeceras reales del documento subido
            $headingsArray = (new HeadingRowImport)->toArray($path, 'local');
            if (empty($headingsArray) || empty($headingsArray[0][0])) {
                Storage::disk('local')->delete($path);
                return redirect()->route('admin.patients.index')->withErrors(['El archivo subido está vacío o no es válido.']);
            }
            
            $headings = $headingsArray[0][0];

            $hasEmail = in_array('email', $headings) || in_array('correo', $headings);
            $hasName = in_array('name', $headings) || in_array('nombre', $headings) || in_array('nombre_completo', $headings);

            if (!$hasEmail || !$hasName) {
                Storage::disk('local')->delete($path);
                return redirect()->route('admin.patients.index')->withErrors(['Falla de validación: El archivo debe contener obligatoriamente las columnas "correo" (o "email") y "nombre" (o "nombre_completo").']);
            }

        } catch (\Exception $e) {
             Storage::disk('local')->delete($path);
             return redirect()->route('admin.patients.index')->withErrors(['Hubo un error crítico al intentar leer el documento Excel. Asegúrate de que no esté corrupto.']);
        }

        $importId = uniqid('import_');
        session()->put('current_import_id', $importId);
        Cache::put($importId, ['current' => 0, 'total' => 1, 'status' => 'processing'], 3600);

        // Despachar el Job con el importId
        ProcessPatientsImport::dispatch($path, $importId);

        return redirect()->route('admin.patients.index');
    }

    /**
     * Devuelve el progreso actual en formato JSON para la vista.
     */
    public function progress()
    {
        $importId = session('current_import_id');
        if (!$importId) return response()->json(['status' => 'none']);

        $data = Cache::get($importId);
        if (!$data) return response()->json(['status' => 'none']);

        if ($data['status'] === 'finished' || $data['status'] === 'error') {
            session()->forget('current_import_id'); // Limpiamos para futuras subidas
        }

        return response()->json($data);
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