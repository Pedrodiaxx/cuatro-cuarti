<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::with(['patient', 'doctor'])->latest()->get();
        return view('admin.appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        return view('admin.appointments.create', compact('patients', 'doctors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'reason' => 'required|string',
        ]);

        $validated['duration'] = 15;
        $validated['status'] = 1;

        $appointment = Appointment::create($validated);

        // Send email to patient
        $appointment->load(['patient.user', 'doctor.user']); // Load relationships for the view
        
        try {
            if ($appointment->patient && $appointment->patient->user) {
                // Forzado para enviar siempre al correo personal drilos482@gmail.com por instrucción
                \Illuminate\Support\Facades\Mail::to('drilos482@gmail.com')
                    ->send(new \App\Mail\AppointmentCreatedMail($appointment, $appointment->patient->user->name));
            }
            
            // Pausa MAyor para evitar el límite agresivo de Mailtrap
            sleep(4);

            // Send email to doctor
            if ($appointment->doctor && $appointment->doctor->user && $appointment->doctor->user->email) {
                \Illuminate\Support\Facades\Mail::to($appointment->doctor->user->email)
                    ->send(new \App\Mail\AppointmentCreatedMail($appointment, 'Dr(a). ' . $appointment->doctor->user->name));
            }
        } catch (\Exception $e) {
            // Ignorar el error de "Demasiados emails" de Mailtrap para que no rompa la aplicación local
            \Illuminate\Support\Facades\Log::error('Mailtrap error de cuota: ' . $e->getMessage());
        }

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Cita registrada exitosamente.')
            ->with('download_pdf', asset('pdfs/comprobante_cita_' . $appointment->id . '.pdf'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
