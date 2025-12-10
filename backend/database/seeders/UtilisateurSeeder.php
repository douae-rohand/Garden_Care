<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UtilisateurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('utilisateur')->insert([
            [
                'nom' => 'Admin',
                'prenom' => 'System',
                'email' => 'admin@servicepro.com',
                'password' => Hash::make('password123'),
                'telephone' => '0612345678',
                'url' => null,
                'google_pw' => null,
                'address' => '123 Rue Principale, Casablanca',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Alami',
                'prenom' => 'Mohammed',
                'email' => 'mohammed.alami@example.com',
                'password' => Hash::make('password123'),
                'telephone' => '0623456789',
                'url' => null,
                'google_pw' => null,
                'address' => '45 Avenue Hassan II, Rabat',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Benjelloun',
                'prenom' => 'Fatima',
                'email' => 'fatima.benjelloun@example.com',
                'password' => Hash::make('password123'),
                'telephone' => '0634567890',
                'url' => null,
                'google_pw' => null,
                'address' => '78 Boulevard Zerktouni, Casablanca',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'El Fassi',
                'prenom' => 'Karim',
                'email' => 'karim.elfassi@example.com',
                'password' => Hash::make('password123'),
                'telephone' => '0645678901',
                'url' => null,
                'google_pw' => null,
                'address' => '12 Rue de la Liberté, Marrakech',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Tazi',
                'prenom' => 'Amina',
                'email' => 'amina.tazi@example.com',
                'password' => Hash::make('password123'),
                'telephone' => '0656789012',
                'url' => null,
                'google_pw' => null,
                'address' => '34 Avenue Mohammed V, Fes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Benslimane',
                'prenom' => 'Youssef',
                'email' => 'youssef.benslimane@example.com',
                'password' => Hash::make('password123'),
                'telephone' => '0667890123',
                'url' => null,
                'google_pw' => null,
                'address' => '56 Rue Ibn Battouta, Tanger',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Chraibi',
                'prenom' => 'Sara',
                'email' => 'sara.chraibi@example.com',
                'password' => Hash::make('password123'),
                'telephone' => '0678901234',
                'url' => null,
                'google_pw' => null,
                'address' => '89 Boulevard Anfa, Casablanca',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Radi',
                'prenom' => 'Hassan',
                'email' => 'hassan.radi@example.com',
                'password' => Hash::make('password123'),
                'telephone' => '0689012345',
                'url' => null,
                'google_pw' => null,
                'address' => '23 Avenue des FAR, Agadir',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
