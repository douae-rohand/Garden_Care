<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IntervenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('intervenant')->insert([
            [
                'id' => 5, // References utilisateur with id=5
                'address' => '34 Avenue Mohammed V, Fes',
                'ville' => 'Fes',
                'bio' => 'Jardinière professionnelle avec 8 ans d\'expérience en entretien de jardins et aménagement paysager.',
                'is_active' => true,
                'admin_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6, // References utilisateur with id=6
                'address' => '56 Rue Ibn Battouta, Tanger',
                'ville' => 'Tanger',
                'bio' => 'Expert en jardinage avec spécialisation en taille d\'arbres et débroussaillage.',
                'is_active' => true,
                'admin_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7, // References utilisateur with id=7
                'address' => '89 Boulevard Anfa, Casablanca',
                'ville' => 'Casablanca',
                'bio' => 'Professionnelle du ménage avec 6 ans d\'expérience dans le nettoyage résidentiel et commercial.',
                'is_active' => true,
                'admin_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8, // References utilisateur with id=8
                'address' => '23 Avenue des FAR, Agadir',
                'ville' => 'Agadir',
                'bio' => 'Spécialiste du ménage en profondeur et nettoyage après travaux.',
                'is_active' => true,
                'admin_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
