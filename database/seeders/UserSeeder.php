<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        try {
            User::factory(10)->create();
            $this->command->info('Users seeded successfully');
        } catch (\Exception $e) {
            $this->command->error('Error seeding users: ' . $e->getMessage());
        }
    }
}
