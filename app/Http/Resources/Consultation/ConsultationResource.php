<?php

namespace App\Http\Resources\Consultation;

use App\Http\Resources\Doctor\DoctorResource;
use App\Http\Resources\Patient\PatientResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsultationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'doctor' => new DoctorResource($this->doctor),
            'patient' => new PatientResource($this->patient),
            'date' => $this->date,
            'description' => $this->description,
        ];
    }
}
