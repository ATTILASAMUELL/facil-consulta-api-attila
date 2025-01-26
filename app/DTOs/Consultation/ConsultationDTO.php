<?php

namespace App\DTOs\Consultation;

class ConsultationDTO
{
    public int $doctor_id;
    public int $patient_id;
    public ?string $date;

    public function __construct(int $doctor_id, int $patient_id, ?string $date)
    {
        $this->doctor_id = $doctor_id;
        $this->patient_id = $patient_id;
        $this->date = $date;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['doctor_id'],
            $data['patient_id'],
            $data['date'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'doctor_id' => $this->doctor_id,
            'patient_id' => $this->patient_id,
            'date' => $this->date
        ];
    }
}
