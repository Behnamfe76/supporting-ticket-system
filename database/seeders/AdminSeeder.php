<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            "email" => "admin@gmail.com",
        ]);
        $superAdmin = User::factory()->create([
            "email" => "super-admin@gmail.com",
        ]);

        $admin->assignRole("admin");
        $superAdmin->assignRole("super-admin");
    }
}
