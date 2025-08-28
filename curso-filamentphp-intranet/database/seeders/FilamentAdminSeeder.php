<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class FilamentAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('FILAMENT_EMAIL', 'admin@example.com');
        $name  = env('FILAMENT_NAME', 'Admin');
        $pass  = env('FILAMENT_PASSWORD', 'Admin123!');

        User::query()->updateOrCreate(
            ['email' => $email],
            ['name' => $name, 'password' => Hash::make($pass)]
        );
    }
}
