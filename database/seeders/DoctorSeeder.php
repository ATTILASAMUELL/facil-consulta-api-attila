<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;

class DoctorSeeder extends Seeder
{
    public function run()
    {
        try {
            Doctor::factory(10)->create();
            $this->command->info('Doctors seeded successfully');
        } catch (\Exception $e) {
            $this->command->error('Error seeding doctors: ' . $e->getMessage());
        }
    }
}
