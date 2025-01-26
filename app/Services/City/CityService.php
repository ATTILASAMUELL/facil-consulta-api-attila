<?php

namespace App\Services\City;

use App\Repositories\City\CityRepository;
use Illuminate\Database\Eloquent\Collection;

class CityService
{
    protected CityRepository $cityRepository;

    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    public function getAllCities(): Collection
    {
        return $this->cityRepository->getAll();
    }

    public function searchCitiesByName(string $name): Collection
    {
        return $this->cityRepository->searchByName($name);
    }
}
