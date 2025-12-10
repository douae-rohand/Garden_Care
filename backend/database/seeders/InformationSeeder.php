<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('information')->insert([
            ['nom' => 'Surface à traiter', 'description' => 'Surface en mètres carrés', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Nombre de pièces', 'description' => 'Nombre de pièces concernées', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Type de matériau', 'description' => 'Matériau à utiliser ou traiter', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Accès difficile', 'description' => 'Précisions sur l\'accessibilité', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Urgence', 'description' => 'Niveau d\'urgence de l\'intervention', 'created_at' => now(), 'updated_at' => now()],
            ['nom' => 'Étage', 'description' => 'Étage de l\'intervention', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
