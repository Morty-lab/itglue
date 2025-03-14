<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();


        if (!User::where('email', 'admin22@example.com')->exists()) {
            User::factory()->create([
                'username' => 'Test Admin',
                'email' => 'admin@example.com',
                'role' => 'admin',
            ]);
        }

        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'username' => 'Test User',
                'email' => 'test@example.com',
            ]);




        }


        $this->call(CompanyInformationSeeder::class);

    }
}
