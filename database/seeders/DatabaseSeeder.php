<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        try {
            $this->call([
                UserSeeder::class,
                ConsultationSeeder::class,
                CitySeeder::class,
                DoctorSeeder::class,
                PatientSeeder::class,
                ConsultationSeeder::class,
            ]);
            $this->command->info('Database seeded successfully!');
        } catch (\Exception $e) {
            $this->command->error('Error seeding database: ' . $e->getMessage());
        }
    }
}
