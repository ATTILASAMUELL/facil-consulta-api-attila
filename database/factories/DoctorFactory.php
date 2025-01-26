<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorFactory extends Factory
{
    protected $model = Doctor::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'specialty' => $this->faker->word,
            'city_id' => City::factory(),
        ];
    }
}
