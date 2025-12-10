<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TacheMaterielSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tache_materiel')->insert([
            // Gardening tasks materials
            ['tache_id' => 1, 'materiel_id' => 1], // Tonte - Tondeuse
            ['tache_id' => 2, 'materiel_id' => 2], // Taille - Taille-haie
            ['tache_id' => 2, 'materiel_id' => 5], // Taille - Sécateur
            ['tache_id' => 3, 'materiel_id' => 3], // Débroussaillage - Débroussailleuse
            ['tache_id' => 4, 'materiel_id' => 4], // Aménagement - Râteau
            ['tache_id' => 4, 'materiel_id' => 5], // Aménagement - Sécateur
            ['tache_id' => 5, 'materiel_id' => 6], // Arrosage - Arrosoir
            // Cleaning tasks materials
            ['tache_id' => 6, 'materiel_id' => 7], // Ménage standard - Aspirateur
            ['tache_id' => 6, 'materiel_id' => 9], // Ménage standard - Balai
            ['tache_id' => 6, 'materiel_id' => 12], // Ménage standard - Produits
            ['tache_id' => 7, 'materiel_id' => 7], // Ménage profondeur - Aspirateur
            ['tache_id' => 7, 'materiel_id' => 8], // Ménage profondeur - Nettoyeur vapeur
            ['tache_id' => 7, 'materiel_id' => 12], // Ménage profondeur - Produits
            ['tache_id' => 8, 'materiel_id' => 10], // Nettoyage vitres - Raclette
            ['tache_id' => 9, 'materiel_id' => 11], // Repassage - Fer à repasser
            ['tache_id' => 10, 'materiel_id' => 7], // Après travaux - Aspirateur
            ['tache_id' => 10, 'materiel_id' => 12], // Après travaux - Produits
        ]);
    }
}
