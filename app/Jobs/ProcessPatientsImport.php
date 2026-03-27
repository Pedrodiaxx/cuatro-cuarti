<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PatientsImport;

class ProcessPatientsImport implements ShouldQueue
{
    use Queueable;

    public $path;
    public $importId;

    /**
     * Create a new job instance.
     */
    public function __construct(string $path, string $importId)
    {
        $this->path = $path;
        $this->importId = $importId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Ejecutar el import
            Excel::import(new PatientsImport($this->importId), $this->path, 'local');

            // Marcar finalizado en cache
            $data = Cache::get($this->importId, ['current' => 0, 'total' => 1]);
            $data['status'] = 'finished';
            $data['current'] = $data['total']; 
            Cache::put($this->importId, $data, 3600);
            
        } catch (\Throwable $e) {
            \Log::error('PatientImport Critical Error: ' . $e->getMessage() . '. File: ' . $e->getFile() . ':' . $e->getLine());
            $data = Cache::get($this->importId, ['current' => 0, 'total' => 1]);
            $data['status'] = 'error';
            $data['message'] = $e->getMessage();
            Cache::put($this->importId, $data, 3600);
            
        } finally {
            Storage::disk('local')->delete($this->path);
        }
    }
}
