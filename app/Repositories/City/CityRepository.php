<?php

namespace App\Repositories\City;

use App\Models\City;
use Illuminate\Database\Eloquent\Collection;

class CityRepository
{
    public function getAll(): Collection
    {
        return City::with('doctors')->orderBy('name')->get();
    }

    public function searchByName(string $name): Collection
    {
        return City::where('name', 'like', '%' . $name . '%')
                   ->with('doctors')
                   ->orderBy('name')
                   ->get();
    }
}
