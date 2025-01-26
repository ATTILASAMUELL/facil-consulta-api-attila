<?php

namespace App\Services\Patient;

use App\Repositories\Patient\PatientRepository;
use App\DTOs\Patient\CreatePatientDTO;
use App\DTOs\Patient\UpdatePatientDTO;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Collection;

class PatientService
{
    protected PatientRepository $patientRepository;

    public function __construct(PatientRepository $patientRepository)
    {
        $this->patientRepository = $patientRepository;
    }

    public function createPatient(CreatePatientDTO $dto): Patient
    {
        return $this->patientRepository->create($dto->toArray());
    }

    public function updatePatient(int $id, UpdatePatientDTO $dto): Patient
    {
        $patient = $this->patientRepository->getById($id);

        if (!$patient) {
            throw new \Exception('Patient not found.');
        }

        $data = $dto->toArray();
        $data = array_filter($data, function ($value) {
            return !is_null($value);
        });

        return $this->patientRepository->update($id, $data);
    }


    public function getPatientsByDoctor(int $doctorId, bool $onlyScheduled, string $cityName = ''): Collection
    {
        return $this->patientRepository->findPatientsByDoctor($doctorId, $onlyScheduled, $cityName);
    }
}
