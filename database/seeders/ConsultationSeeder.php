<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Consultation;

class ConsultationSeeder extends Seeder
{
    public function run()
    {
        try {
            Consultation::factory(30)->create();
            $this->command->info('Consultations seeded successfully');
        } catch (\Exception $e) {
            $this->command->error('Error seeding consultations: ' . $e->getMessage());
        }
    }
}
