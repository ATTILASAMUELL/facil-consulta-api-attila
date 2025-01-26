<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;

class PatientSeeder extends Seeder
{
    public function run()
    {
        try {
            Patient::factory(20)->create();
            $this->command->info('Patients seeded successfully');
        } catch (\Exception $e) {
            $this->command->error('Error seeding patients: ' . $e->getMessage());
        }
    }
}
