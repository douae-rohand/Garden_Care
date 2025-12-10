<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InterventionMaterielSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('intervention_materiel')->insert([
            // Intervention 1 (Tonte de pelouse)
            ['intervention_id' => 1, 'materiel_id' => 1], // Tondeuse
            // Intervention 2 (Débroussaillage)
            ['intervention_id' => 2, 'materiel_id' => 3], // Débroussailleuse
            // Intervention 3 (Ménage standard)
            ['intervention_id' => 3, 'materiel_id' => 7], // Aspirateur
            ['intervention_id' => 3, 'materiel_id' => 9], // Balai
            // Intervention 4 (Ménage profondeur)
            ['intervention_id' => 4, 'materiel_id' => 7], // Aspirateur
            ['intervention_id' => 4, 'materiel_id' => 8], // Nettoyeur vapeur
            // Intervention 5 (Taille de haies)
            ['intervention_id' => 5, 'materiel_id' => 2], // Taille-haie
            ['intervention_id' => 5, 'materiel_id' => 5], // Sécateur
        ]);
    }
}
