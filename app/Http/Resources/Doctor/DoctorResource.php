<?php

namespace App\Http\Resources\Doctor;

use App\Http\Resources\City\CityResource;
use App\Http\Resources\Consultation\ConsultationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'specialty' => $this->specialty,
            'city' => new CityResource($this->whenLoaded('city')),
            'consultations' => ConsultationResource::collection($this->whenLoaded('consultations')),
        ];
    }
}
