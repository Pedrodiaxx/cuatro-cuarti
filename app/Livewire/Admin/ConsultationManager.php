<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Appointment;

class ConsultationManager extends Component
{
    public Appointment $appointment;
    public $activeTab = 'consulta'; // 'consulta' or 'receta'
    
    // Consulta fields
    public $diagnosis = '';
    public $treatment = '';
    public $notes = '';

    // Receta fields
    public $medications = [];

    // History
    public $showHistoryModal = false;
    public $showMedicalHistoryModal = false;
    public $pastConsultations = [];

    public function mount(Appointment $appointment)
    {
        $this->appointment = $appointment->load(['patient.user', 'patient.bloodType', 'doctor.user']);
        
        // Initialize with one empty medication slot if empty
        $this->addMedication();

        // Load past consultations including those without diagnosis to show they exist
        $this->pastConsultations = Appointment::with(['doctor.user'])
            ->where('patient_id', $appointment->patient_id)
            ->where('id', '!=', $appointment->id)
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();
    }

    public function addMedication()
    {
        $this->medications[] = [
            'name' => '',
            'dose' => '',
            'frequency' => ''
        ];
    }

    public function removeMedication($index)
    {
        unset($this->medications[$index]);
        $this->medications = array_values($this->medications);
        
        if (empty($this->medications)) {
            $this->addMedication();
        }
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function toggleHistoryModal()
    {
        $this->showHistoryModal = !$this->showHistoryModal;
    }

    public function toggleMedicalHistoryModal()
    {
        $this->showMedicalHistoryModal = !$this->showMedicalHistoryModal;
    }

    public function saveConsultation()
    {
        $this->appointment->update([
            'status' => 2,
            'diagnosis' => $this->diagnosis,
            'treatment' => $this->treatment,
            'notes' => $this->notes,
            'prescription_json' => json_encode($this->medications),
        ]);

        session()->flash('swall', [
            'icon' => 'success',
            'title' => 'Consulta Guardada',
            'text' => 'Los datos de la consulta han sido registrados exitosamente.',
        ]);

        return redirect()->route('admin.appointments.index');
    }

    public function render()
    {
        return view('livewire.admin.consultation-manager')->layout('layouts.admin', ['title' => 'Gestión de Consulta']);
    }
}
