<?php

namespace App\Repositories\Doctor;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Collection;

class DoctorRepository
{
    public function getAll(): Collection
    {
        return Doctor::with(['city', 'consultations'])->orderBy('name')->get();
    }

    public function searchByName(string $name): Collection
    {
        $cleanedName = preg_replace('/^(dr|dra)\s+/i', '', $name);

        return Doctor::with(['city', 'consultations'])
                     ->where('name', 'like', '%' . $cleanedName . '%')
                     ->orderBy('name')
                     ->get();
    }

    public function findByCityAndName(int $cityId, string $name = ''): Collection
    {
        $query = Doctor::with(['city', 'consultations'])
            ->where('city_id', $cityId);

        if ($name) {
            $cleanedName = preg_replace('/^(dr|dra)\s+/i', '', $name);
            $query->where('name', 'like', '%' . $cleanedName . '%');
        }

        return $query->orderBy('name')->get();
    }

    public function findByCityAndSpecialty(int $cityId, string $specialty = ''): Collection
    {
        $query = Doctor::with(['city', 'consultations'])
            ->where('city_id', $cityId);

        if ($specialty) {
            $query->where('specialty', 'like', '%' . $specialty . '%');
        }

        return $query->orderBy('name')->get();
    }
}
