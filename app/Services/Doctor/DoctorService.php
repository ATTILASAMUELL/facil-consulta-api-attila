<?php

namespace App\Services\Doctor;

use App\Repositories\Doctor\DoctorRepository;
use Illuminate\Database\Eloquent\Collection;

class DoctorService
{
    protected DoctorRepository $doctorRepository;

    public function __construct(DoctorRepository $doctorRepository)
    {
        $this->doctorRepository = $doctorRepository;
    }

    public function getAllDoctors(): Collection
    {
        return $this->doctorRepository->getAll();
    }


    public function searchDoctorsByName(string $name): Collection
    {
        return $this->doctorRepository->searchByName($name);
    }

    public function getDoctorsByCity(int $cityId, string $name = ''): Collection
    {
        return $this->doctorRepository->findByCityAndName($cityId, $name);
    }

    public function getDoctorsByCityAndSpecialty(int $cityId, string $specialty = ''): Collection
    {
        return $this->doctorRepository->findByCityAndSpecialty($cityId, $specialty);
    }
}
