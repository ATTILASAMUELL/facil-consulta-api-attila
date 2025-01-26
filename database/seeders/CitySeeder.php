<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;

class CitySeeder extends Seeder
{
    public function run()
    {
        try {
            City::factory(10)->create();
            $this->command->info('Cities seeded successfully');
        } catch (\Exception $e) {
            $this->command->error('Error seeding cities: ' . $e->getMessage());
        }
    }
}
