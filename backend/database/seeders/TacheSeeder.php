<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TacheSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tache')->insert([
            // Jardinage tasks
            [
                'service_id' => 1,
                'nom_tache' => 'Tonte de pelouse',
                'description' => 'Tonte régulière de la pelouse avec ramassage des déchets verts',
                'status' => 'disponible',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_id' => 1,
                'nom_tache' => 'Taille de haies',
                'description' => 'Taille et entretien des haies, arbustes et buissons',
                'status' => 'disponible',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_id' => 1,
                'nom_tache' => 'Débroussaillage',
                'description' => 'Débroussaillage de terrains et espaces verts',
                'status' => 'disponible',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_id' => 1,
                'nom_tache' => 'Aménagement paysager',
                'description' => 'Conception et réalisation d\'espaces verts',
                'status' => 'disponible',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_id' => 1,
                'nom_tache' => 'Arrosage et entretien',
                'description' => 'Arrosage des plantes et entretien général du jardin',
                'status' => 'disponible',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Ménage tasks
            [
                'service_id' => 2,
                'nom_tache' => 'Ménage standard',
                'description' => 'Nettoyage régulier des pièces, dépoussiérage, aspiration',
                'status' => 'disponible',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_id' => 2,
                'nom_tache' => 'Ménage en profondeur',
                'description' => 'Grand nettoyage avec désinfection complète',
                'status' => 'disponible',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_id' => 2,
                'nom_tache' => 'Nettoyage vitres',
                'description' => 'Nettoyage de toutes les vitres intérieures et extérieures',
                'status' => 'disponible',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_id' => 2,
                'nom_tache' => 'Repassage',
                'description' => 'Service de repassage du linge',
                'status' => 'disponible',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'service_id' => 2,
                'nom_tache' => 'Nettoyage après travaux',
                'description' => 'Nettoyage complet après rénovation ou construction',
                'status' => 'disponible',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
