<?php

namespace App\Repositories\Patient;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class PatientRepository
{
    public function create(array $data): Patient
    {
        return Patient::create($data);
    }

    public function update(int $id, array $data): Patient
    {
        $patient = Patient::find($id);
        $patient->update($data);
        return $patient;
    }

    public function getAll(): Collection
    {
        return Patient::all();
    }

    public function getById(int $id): Patient
    {
        return Patient::findOrFail($id);
    }

    public function getAllPatientsByDoctor(int $doctor_id, ?string $name = null): Collection
    {
        $query = Patient::whereHas('consultations', function ($query) use ($doctor_id) {
            $query->where('doctor_id', $doctor_id);
        });

        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        return $query->orderBy('name')->get();
    }

    public function getScheduledPatientsByDoctor(int $doctor_id, ?string $name = null): Collection
    {
        $query = Patient::whereHas('consultations', function ($query) use ($doctor_id) {
            $query->where('doctor_id', $doctor_id)
                  ->where('date', '>=', now());
        });

        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        return $query->orderBy('name')->get();
    }

    public function findPatientsByDoctor(int $doctorId, bool $onlyScheduled, string $cityName = ''): Collection
    {
        $query = Patient::select('patients.*')
            ->join('consultations', 'consultations.patient_id', '=', 'patients.id')
            ->where('consultations.doctor_id', $doctorId);

        if ($onlyScheduled) {
            $query->whereNull('consultations.date'); // Consultas agendadas (sem data real)
        } else {
            $query->whereNotNull('consultations.date'); // Consultas realizadas (com data)
        }

        if ($cityName) {
            $query->whereHas('consultations.city', function($query) use ($cityName) {
                $query->where('name', 'like', '%' . $cityName . '%');
            });
        }

        Log::info('Query being executed:', ['query' => $query->toSql(), 'bindings' => $query->getBindings()]); // Adicionando log para verificar a consulta

        return $query->orderBy('consultations.date', 'asc')->get();
    }

}
