<?php

namespace App\Livewire\Admin;

use App\Models\Appointment;
use Livewire\Component;

class ConsultationManager extends Component
{
    public Appointment $appointment;
    
    // Default Tab
    public $activeTab = 'consulta';

    // Form fields
    public $diagnosis;
    public $treatment;
    public $notes;
    
    // Dynamic Receta (Medications)
    public $medications = [];

    // Modal
    public $showHistoryModal = false;
    public $previousConsultations = [];

    protected $rules = [
        'diagnosis' => 'required|string',
        'treatment' => 'required|string',
        'notes' => 'nullable|string',
        'medications' => 'array',
        'medications.*.name' => 'required|string',
        'medications.*.dosage' => 'required|string',
        'medications.*.frequency' => 'required|string',
    ];

    protected $messages = [
        'diagnosis.required' => 'El campo diagnóstico es obligatorio.',
        'treatment.required' => 'El campo tratamiento es obligatorio.',
        'medications.*.name.required' => 'Ingresa el nombre del medicamento.',
        'medications.*.dosage.required' => 'Ingresa la dosis.',
        'medications.*.frequency.required' => 'Ingresa la frecuencia.',
    ];

    public function mount(Appointment $appointment)
    {
        $this->appointment = $appointment;
        $this->diagnosis = $appointment->diagnosis;
        $this->treatment = $appointment->treatment;
        $this->notes = $appointment->notes;
        $this->medications = $appointment->medications ?? [];
        
        $this->appointment->load('patient.user');
        
        // Start with empty medication if empty to encourage filling
        if (empty($this->medications)) {
            // $this->addMedication(); // optional
        }
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function addMedication()
    {
        $this->medications[] = ['name' => '', 'dosage' => '', 'frequency' => ''];
    }

    public function removeMedication($index)
    {
        unset($this->medications[$index]);
        $this->medications = array_values($this->medications); // Re-index array
    }

    public function openHistoryModal()
    {
        $this->previousConsultations = Appointment::where('patient_id', $this->appointment->patient_id)
                                                    ->where('id', '!=', $this->appointment->id)
                                                    ->whereNotNull('diagnosis')
                                                    ->with('doctor.user')
                                                    ->orderBy('date', 'desc')
                                                    ->get();
        $this->showHistoryModal = true;
    }

    public function closeHistoryModal()
    {
        $this->showHistoryModal = false;
    }

    public function save()
    {
        $this->validate();

        $this->appointment->update([
            'diagnosis' => $this->diagnosis,
            'treatment' => $this->treatment,
            'notes' => $this->notes,
            'medications' => $this->medications,
            'status' => 2, // asuming 2 is "Completado"
        ]);

        session()->flash('success', 'Consulta médica completada y receta guardada.');

        return redirect()->route('admin.appointments.index');
    }

    public function render()
    {
        return view('livewire.admin.consultation-manager')->layout('layouts.admin', [
            'title' => 'Consulta | Pedrini',
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
                ['name' => 'Citas', 'href' => route('admin.appointments.index')],
                ['name' => 'Consulta'],
            ]
        ]);
    }
}
