<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\{User};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(10)->hasContacts(10)->create();

        User::factory()->hasContacts(10)->create([
            'name'     => 'Test User',
            'email'    => 'test@example.com',
            'password' => 'Password1',
        ]);
    }
}
