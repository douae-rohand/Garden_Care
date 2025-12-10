<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IntervenantMaterielSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('intervenant_materiel')->insert([
            // Intervenant 5 (Jardinier) - gardening tools
            ['intervenant_id' => 5, 'materiel_id' => 1], // Tondeuse
            ['intervenant_id' => 5, 'materiel_id' => 2], // Taille-haie
            ['intervenant_id' => 5, 'materiel_id' => 4], // Râteau
            ['intervenant_id' => 5, 'materiel_id' => 5], // Sécateur
            ['intervenant_id' => 5, 'materiel_id' => 6], // Arrosoir
            // Intervenant 6 (Jardinier) - gardening tools
            ['intervenant_id' => 6, 'materiel_id' => 1], // Tondeuse
            ['intervenant_id' => 6, 'materiel_id' => 2], // Taille-haie
            ['intervenant_id' => 6, 'materiel_id' => 3], // Débroussailleuse
            ['intervenant_id' => 6, 'materiel_id' => 5], // Sécateur
            // Intervenant 7 (Ménage) - cleaning tools
            ['intervenant_id' => 7, 'materiel_id' => 7], // Aspirateur
            ['intervenant_id' => 7, 'materiel_id' => 8], // Nettoyeur vapeur
            ['intervenant_id' => 7, 'materiel_id' => 9], // Balai
            ['intervenant_id' => 7, 'materiel_id' => 10], // Raclette vitres
            ['intervenant_id' => 7, 'materiel_id' => 11], // Fer à repasser
            // Intervenant 8 (Ménage) - cleaning tools
            ['intervenant_id' => 8, 'materiel_id' => 7], // Aspirateur
            ['intervenant_id' => 8, 'materiel_id' => 9], // Balai
            ['intervenant_id' => 8, 'materiel_id' => 12], // Produits nettoyage
        ]);
    }
}
