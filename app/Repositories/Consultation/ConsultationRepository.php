<?php

namespace App\Repositories\Consultation;

use App\Models\Consultation;
use Illuminate\Database\Eloquent\Collection;
use Exception;

class ConsultationRepository
{
    public function create(array $data): Consultation
    {
        return Consultation::create([
            'doctor_id' => $data['doctor_id'],
            'patient_id' => $data['patient_id'],
            'date' => $data['date'],
        ]);
    }

    public function update(int $id, array $data): Consultation
    {
        $consultation = Consultation::find($id);
        if ($consultation) {
            $consultation->update($data);
            return $consultation;
        }

        throw new Exception('Consultation not found.');
    }

    public function getAll(): Collection
    {
        return Consultation::with(['doctor', 'patient'])->orderBy('date')->get();
    }

    public function getById(int $id): Consultation
    {
        return Consultation::with(['doctor', 'patient'])->findOrFail($id);
    }

    public function findByDoctorAndPatientAndDate(int $doctorId, int $patientId, string $date): ?Consultation
    {
        return Consultation::where('doctor_id', $doctorId)
                           ->where('patient_id', $patientId)
                           ->where('date', $date)
                           ->first();
    }
}
