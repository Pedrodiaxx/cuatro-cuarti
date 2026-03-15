<?php

namespace App\Livewire\Admin;

use Livewire\Component;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Carbon\Carbon;

class DoctorScheduleManager extends Component
{
    public $doctor;
    public $days = ['LUNES', 'MARTES', 'MIÉRCOLES', 'JUEVES', 'VIERNES', 'SÁBADO', 'DOMINGO'];
    public $timeBlocks = [];
    public $schedule = []; // key: "day-time", value: bool

    public function mount(Doctor $doctor)
    {
        $this->doctor = $doctor->load('user');
        $this->generateTimeBlocks();
        $this->loadSchedule();
    }

    public function generateTimeBlocks()
    {
        // Generate blocks from 08:00 to 20:00 every 1 hour (as starting points)
        // Mockup shows 08:00:00, 09:00:00...
        for ($hour = 8; $hour <= 19; $hour++) {
            $time = sprintf('%02d:00:00', $hour);
            $subSlots = [];
            // Subslots of 15 mins for each hour
            for ($min = 0; $min < 60; $min += 15) {
                $subSlots[] = sprintf('%02d:%02d:00', $hour, $min);
            }
            $this->timeBlocks[] = [
                'main' => $time,
                'slots' => $subSlots
            ];
        }
    }

    public function loadSchedule()
    {
        $saved = DoctorSchedule::where('doctor_id', $this->doctor->id)->get();
        foreach ($saved as $s) {
            $key = $s->day_of_week . '-' . $s->start_time;
            $this->schedule[$key] = $s->is_available;
        }
    }

    public function toggleSlot($dayIndex, $time)
    {
        $key = $dayIndex . '-' . $time;
        $this->schedule[$key] = !($this->schedule[$key] ?? false);
    }

    public function toggleAllDay($dayIndex)
    {
        // For simplicity, let's just toggle all in that day based on if ANY is selected
        $anySelected = false;
        foreach ($this->timeBlocks as $block) {
            foreach ($block['slots'] as $slot) {
                if ($this->schedule[$dayIndex . '-' . $slot] ?? false) {
                    $anySelected = true;
                    break 2;
                }
            }
        }

        foreach ($this->timeBlocks as $block) {
            foreach ($block['slots'] as $slot) {
                $this->schedule[$dayIndex . '-' . $slot] = !$anySelected;
            }
        }
    }

    public function toggleAllTime($time)
    {
        // Toggle all days for a specific time slot
        $anySelected = false;
        for ($d = 0; $d < 7; $d++) {
            if ($this->schedule[$d . '-' . $time] ?? false) {
                $anySelected = true;
                break;
            }
        }

        for ($d = 0; $d < 7; $d++) {
            $this->schedule[$d . '-' . $time] = !$anySelected;
        }
    }

    public function saveSchedule()
    {
        // Clear existing for this doctor and save new ones
        DoctorSchedule::where('doctor_id', $this->doctor->id)->delete();

        foreach ($this->schedule as $key => $available) {
            if ($available) {
                list($day, $time) = explode('-', $key);
                
                // Calculate end_time (start + 15 mins)
                $start = Carbon::createFromFormat('H:i:s', $time);
                $end = $start->copy()->addMinutes(15);

                DoctorSchedule::create([
                    'doctor_id' => $this->doctor->id,
                    'day_of_week' => $day,
                    'start_time' => $start->format('H:i:s'),
                    'end_time' => $end->format('H:i:s'),
                    'is_available' => true
                ]);
            }
        }

        session()->flash('swall', [
            'icon' => 'success',
            'title' => 'Horario Guardado',
            'text' => 'La disponibilidad del doctor ha sido actualizada exitosamente',
        ]);

        return redirect()->route('admin.doctors.index');
    }

    public function render()
    {
        return view('livewire.admin.doctor-schedule-manager')->layout('layouts.admin', ['title' => 'Gestor de Horarios | Pedrini']);
    }
}
