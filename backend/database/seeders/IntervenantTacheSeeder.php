<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IntervenantTacheSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('intervenant_tache')->insert([
            // Intervenant 5 (Jardinière) can do gardening tasks
            ['intervenant_id' => 5, 'tache_id' => 1], // Tonte
            ['intervenant_id' => 5, 'tache_id' => 2], // Taille
            ['intervenant_id' => 5, 'tache_id' => 4], // Aménagement
            ['intervenant_id' => 5, 'tache_id' => 5], // Arrosage
            // Intervenant 6 (Jardinier) can do gardening tasks
            ['intervenant_id' => 6, 'tache_id' => 1], // Tonte
            ['intervenant_id' => 6, 'tache_id' => 2], // Taille
            ['intervenant_id' => 6, 'tache_id' => 3], // Débroussaillage
            // Intervenant 7 (Ménage) can do cleaning tasks
            ['intervenant_id' => 7, 'tache_id' => 6], // Ménage standard
            ['intervenant_id' => 7, 'tache_id' => 7], // Ménage profondeur
            ['intervenant_id' => 7, 'tache_id' => 8], // Vitres
            ['intervenant_id' => 7, 'tache_id' => 9], // Repassage
            // Intervenant 8 (Ménage) can do cleaning tasks
            ['intervenant_id' => 8, 'tache_id' => 6], // Ménage standard
            ['intervenant_id' => 8, 'tache_id' => 7], // Ménage profondeur
            ['intervenant_id' => 8, 'tache_id' => 10], // Après travaux
        ]);
    }
}
