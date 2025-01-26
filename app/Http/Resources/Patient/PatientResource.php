<?php

namespace App\Http\Resources\Patient;

use App\Http\Resources\Consultation\ConsultationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'cpf' => $this->cpf,
            'cellphone' => $this->cellphone,
            'consultations' => ConsultationResource::collection($this->whenLoaded('consultations')),
        ];
    }
}
