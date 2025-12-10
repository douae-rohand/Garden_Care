<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InterventionInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('intervention_information')->insert([
            // Intervention 1 (Réparation fuite)
            ['intervention_id' => 1, 'information_id' => 2, 'information' => '2'], // Nombre de pièces
            ['intervention_id' => 1, 'information_id' => 5, 'information' => 'Élevée'], // Urgence
            // Intervention 2 (Dépannage électrique)
            ['intervention_id' => 2, 'information_id' => 2, 'information' => '3'], // Nombre de pièces
            ['intervention_id' => 2, 'information_id' => 5, 'information' => 'Moyenne'], // Urgence
            ['intervention_id' => 2, 'information_id' => 6, 'information' => '2ème étage'], // Étage
            // Intervention 3 (Tonte de pelouse)
            ['intervention_id' => 3, 'information_id' => 1, 'information' => '200 m²'], // Surface
            // Intervention 4 (Peinture intérieure)
            ['intervention_id' => 4, 'information_id' => 1, 'information' => '80 m²'], // Surface
            ['intervention_id' => 4, 'information_id' => 2, 'information' => '4'], // Nombre de pièces
            ['intervention_id' => 4, 'information_id' => 3, 'information' => 'Acrylique'], // Type de matériau
        ]);
    }
}
