<?php

namespace App\Livewire\Admin;

use Livewire\Component;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;

use App\Models\DoctorSchedule;

class AppointmentManager extends Component
{
    // Search properties
    public $searchDate;
    public $searchStartTime = '07:00';
    public $searchEndTime = '21:00';
    public $searchSpecialty = '';

    // Data lists
    public $availableDoctors = [];
    public $patients = [];

    // Selected data for summary
    public $selectedDoctorId = null;
    public $selectedDoctorName = null;
    public $selectedTime = null;
    
    // Form Inputs
    public $patientId = '';
    public $reason = '';

    public function mount()
    {
        $this->searchDate = date('Y-m-d');
        $this->patients = Patient::with('user')->get();
        
        // Load default available doctors list immediately on mount
        $this->searchAvailability();
    }

    public function searchAvailability()
    {
        $this->validate([
            'searchDate' => 'required|date|after_or_equal:today',
        ], [
            'searchDate.after_or_equal' => 'La fecha no puede ser en el pasado.'
        ]);

        // Get day of week (Lunes=0, Domingo=6)
        $dayOfWeek = Carbon::parse($this->searchDate)->dayOfWeekIso - 1;

        $query = Doctor::with(['user', 'speciality']);
        if(!empty($this->searchSpecialty)) {
            $query->whereHas('speciality', function($q) {
                $q->where('name', 'like', "%{$this->searchSpecialty}%");
            });
        }
        
        $doctors = $query->get();

        $this->availableDoctors = [];
        foreach ($doctors as $doctor) {
            // Find shared availability for the day and time range
            $slots = DoctorSchedule::where('doctor_id', $doctor->id)
                ->where('day_of_week', $dayOfWeek)
                ->where('start_time', '>=', $this->searchStartTime . ':00')
                ->where('start_time', '<=', $this->searchEndTime . ':00')
                ->where('is_available', true)
                ->orderBy('start_time')
                ->get()
                ->map(fn($s) => Carbon::parse($s->start_time)->format('H:i'))
                ->toArray();

            if (count($slots) > 0) {
                $this->availableDoctors[] = [
                    'id' => $doctor->id,
                    'name' => ($doctor->user->name ?? '') . ' ' . ($doctor->user->last_name ?? ''),
                    'specialty' => $doctor->speciality->name ?? 'General',
                    'slots' => $slots
                ];
            }
        }

        // Reset current selection on new search
        $this->selectedDoctorId = null;
        $this->selectedTime = null;
    }

    public function selectTimeSlot($doctorId, $doctorName, $time)
    {
        $this->selectedDoctorId = $doctorId;
        $this->selectedDoctorName = $doctorName;
        $this->selectedTime = $time;
    }

    public function saveAppointment()
    {
        $this->validate([
            'selectedDoctorId' => 'required',
            'selectedTime' => 'required',
            'patientId' => 'required',
        ], [
            'selectedDoctorId.required' => 'Debe seleccionar un doctor y un horario disponible.',
            'selectedTime.required' => 'Debe seleccionar un horario.',
            'patientId.required' => 'Debe seleccionar un paciente.',
        ]);

        // Calculate EndTime based on 15 minutes duration.
        $start = Carbon::parse($this->selectedTime);
        $end = $start->copy()->addMinutes(15);

        Appointment::create([
            'doctor_id'  => $this->selectedDoctorId,
            'patient_id' => $this->patientId,
            'date'       => $this->searchDate,
            'start_time' => $start->format('H:i:s'),
            'end_time'   => $end->format('H:i:s'),
            'duration'   => 15,
            'reason'     => $this->reason,
            'status'     => 1,
        ]);

        session()->flash('swall', [
            'icon' => 'success',
            'title' => 'Cita Creada',
            'text' => 'La cita fue registrada exitosamente',
        ]);

        return redirect()->route('admin.appointments.index');
    }

    public function render()
    {
        return view('livewire.admin.appointment-manager')->layout('layouts.admin', ['title' => 'Nueva Cita | Pedrini']);
    }
}
