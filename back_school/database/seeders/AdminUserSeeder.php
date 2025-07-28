<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Creation de l'administrateur par défaut
     * avec un mot de passe par défaut.
     * Exec: php artisan db:seed --class=AdminUserSeeder
     */
    public function run(): void
    {
        User::create([
            'nom' => 'Super',
            'prenom' => 'Admin',
            'email' => 'sihamoudineanzize@gmail.com',
            'password' => Hash::make('Passer123!'),
            'role' => 'admin',
            'must_change_password' => true,
        ]);
    }
}
