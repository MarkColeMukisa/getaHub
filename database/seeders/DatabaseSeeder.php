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
        // Admin account for local development
        User::factory()->create([
            'name' => 'John Gapp',
            'email' => 'joegapp256@gmail.com',
            'password' => bcrypt('password'), // Set a secure password
            'is_admin' => true,
        ]);

        // Seed Tenants
        $this->call([
            TenantsSeeder::class,
            BillsSeeder::class,
        ]);
    }
}
