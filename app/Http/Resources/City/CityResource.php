<?php

namespace App\Http\Resources\City;

use App\Http\Resources\Doctor\DoctorResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'state' => $this->state,
            'doctors' => DoctorResource::collection($this->whenLoaded('doctors')),
        ];
    }
}
