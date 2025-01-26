<?php

namespace App\Services\Consultation;

use App\Repositories\Consultation\ConsultationRepository;
use App\Models\Consultation;
use App\DTOs\Consultation\ConsultationDTO;
use Exception;

class ConsultationService
{
    protected ConsultationRepository $consultationRepository;

    public function __construct(ConsultationRepository $consultationRepository)
    {
        $this->consultationRepository = $consultationRepository;
    }

    public function createConsultation(ConsultationDTO $dto): Consultation
    {
        $data = $dto->toArray();
        $existingConsultation = $this->consultationRepository->findByDoctorAndPatientAndDate($data['doctor_id'], $data['patient_id'], $data['date']);

        if ($existingConsultation) {
            throw new Exception('This patient already has a consultation scheduled with the doctor at the same time.');
        }

        return $this->consultationRepository->create($data);
    }

    public function updateConsultation(int $id, ConsultationDTO $dto): Consultation
    {
        $data = $dto->toArray();
        $consultation = $this->consultationRepository->getById($id);

        if (!$consultation) {
            throw new Exception('Consultation not found.');
        }

        $existingConsultation = $this->consultationRepository->findByDoctorAndPatientAndDate($data['doctor_id'], $data['patient_id'], $data['date']);

        if ($existingConsultation) {
            throw new Exception('This patient already has a consultation scheduled with the doctor at the same time.');
        }

        return $this->consultationRepository->update($id, $data);
    }
}
