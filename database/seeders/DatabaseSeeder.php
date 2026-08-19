<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $owner = User::factory()->create([
            'name' => 'Majid Owner',
            'email' => 'owner@example.com',
            'password' => Hash::make('Password123!'),
        ]);

        $admin = User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('Password123!'),
        ]);

        $member = User::factory()->create([
            'name' => 'Test Member',
            'email' => 'member@example.com',
            'password' => Hash::make('Password123!'),
        ]);

        $organization = Organization::factory()->create([
            'owner_id' => $owner->id,
            'name' => 'Tech Solutions',
            'slug' => 'tech-solutions',
            'description' => 'Demo organization for testing.',
        ]);

        $organization->users()->attach([
            $owner->id => [
                'role' => 'owner',
            ],

            $admin->id => [
                'role' => 'admin',
            ],

            $member->id => [
                'role' => 'member',
            ],
        ]);
    }
}
