<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Ensure test user exists (use updateOrCreate to avoid duplicate errors)
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                // plain password will be hashed by the model's cast
                'password' => 'password',
            ]
        );

        // Admin user for logging into the application:
        // Email: admin@example.com
        // Password: AdminPass123!
        // Use updateOrCreate so running the seeder again will update existing admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'role' => 'admin',
                'is_active' => true,
                // Provide plain password so the model's `password` cast hashes it once
                'password' => 'AdminPass123!',
            ]
        );
    }
}
