<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('commentaire')->insert([
            [
                'intervention_id' => 1,
                'commentaire' => 'Excellent travail ! La fuite a été réparée rapidement et efficacement. Je recommande vivement.',
                'type_auteur' => 'client',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'intervention_id' => 1,
                'commentaire' => 'Client très sympathique et accueillant. Problème résolu sans complications.',
                'type_auteur' => 'intervenant',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'intervention_id' => 2,
                'commentaire' => 'Bon service, l\'électricien était professionnel et a bien expliqué le travail effectué.',
                'type_auteur' => 'client',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'intervention_id' => 2,
                'commentaire' => 'Installation réussie, maison bien organisée ce qui a facilité le travail.',
                'type_auteur' => 'intervenant',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
