<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['UserEmail' => 'admin@example.com'],
            [
                'UserName' => 'ethy',
                'UserPassword' => Hash::make('12345678'), // Choisis un mot de passe fort !
                'Role' => 'admin',
                'UserPhone' => '0854434602', // Optionnel
            ]
        );
    }
}
