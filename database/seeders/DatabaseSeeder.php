<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'admin',
            'role'  => 'admin',
            'email' => 'admin@domaine.fr',
            'email_verified_at' => now(),
            'password' => '$2y$10$/z8tfCto31hIqqXbX9AhHeQd8Bz/fLSSHaMtqODgq5uZNMegJJ9la', // 0000
            'remember_token' => Str::random(10),
        ]);

        \App\Models\Category::factory(20)->create();
    }
}
