<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JustificatifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('justificatif')->insert([
            // Intervenant 5 (Jardinier)
            [
                'intervenant_id' => 5,
                'type' => 'Certificat jardinage',
                'chemin_fichier' => '/uploads/justificatifs/cert_jardinage_5.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'intervenant_id' => 5,
                'type' => 'Assurance professionnelle',
                'chemin_fichier' => '/uploads/justificatifs/assurance_5.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Intervenant 6 (Jardinier)
            [
                'intervenant_id' => 6,
                'type' => 'Diplôme espaces verts',
                'chemin_fichier' => '/uploads/justificatifs/diplome_jardinage_6.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'intervenant_id' => 6,
                'type' => 'Assurance professionnelle',
                'chemin_fichier' => '/uploads/justificatifs/assurance_6.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Intervenant 7 (Ménage)
            [
                'intervenant_id' => 7,
                'type' => 'Certificat hygiène et propreté',
                'chemin_fichier' => '/uploads/justificatifs/cert_menage_7.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Intervenant 8 (Ménage)
            [
                'intervenant_id' => 8,
                'type' => 'Formation nettoyage professionnel',
                'chemin_fichier' => '/uploads/justificatifs/formation_menage_8.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
