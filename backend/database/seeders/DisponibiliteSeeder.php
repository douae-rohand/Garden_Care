<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DisponibiliteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('disponibilite')->insert([
            // Intervenant 5 (Plombier) - Regular availability
            [
                'intervenant_id' => 5,
                'type' => 'reguliere',
                'jours_semaine' => 'lundi',
                'heure_debut' => '08:00:00',
                'heure_fin' => '17:00:00',
                'date_specific' => null,
            ],
            [
                'intervenant_id' => 5,
                'type' => 'reguliere',
                'jours_semaine' => 'mardi',
                'heure_debut' => '08:00:00',
                'heure_fin' => '17:00:00',
                'date_specific' => null,
            ],
            [
                'intervenant_id' => 5,
                'type' => 'reguliere',
                'jours_semaine' => 'mercredi',
                'heure_debut' => '08:00:00',
                'heure_fin' => '17:00:00',
                'date_specific' => null,
            ],
            // Intervenant 6 (Électricien) - Regular availability
            [
                'intervenant_id' => 6,
                'type' => 'reguliere',
                'jours_semaine' => 'lundi',
                'heure_debut' => '09:00:00',
                'heure_fin' => '18:00:00',
                'date_specific' => null,
            ],
            [
                'intervenant_id' => 6,
                'type' => 'reguliere',
                'jours_semaine' => 'jeudi',
                'heure_debut' => '09:00:00',
                'heure_fin' => '18:00:00',
                'date_specific' => null,
            ],
            [
                'intervenant_id' => 6,
                'type' => 'reguliere',
                'jours_semaine' => 'vendredi',
                'heure_debut' => '09:00:00',
                'heure_fin' => '18:00:00',
                'date_specific' => null,
            ],
            // Intervenant 7 (Jardinière) - Regular availability
            [
                'intervenant_id' => 7,
                'type' => 'reguliere',
                'jours_semaine' => 'mardi',
                'heure_debut' => '07:00:00',
                'heure_fin' => '15:00:00',
                'date_specific' => null,
            ],
            [
                'intervenant_id' => 7,
                'type' => 'reguliere',
                'jours_semaine' => 'samedi',
                'heure_debut' => '07:00:00',
                'heure_fin' => '15:00:00',
                'date_specific' => null,
            ],
            // Intervenant 8 (Peintre) - Mixed availability
            [
                'intervenant_id' => 8,
                'type' => 'reguliere',
                'jours_semaine' => 'lundi',
                'heure_debut' => '08:00:00',
                'heure_fin' => '16:00:00',
                'date_specific' => null,
            ],
            [
                'intervenant_id' => 8,
                'type' => 'ponctuelle',
                'jours_semaine' => null,
                'heure_debut' => '10:00:00',
                'heure_fin' => '14:00:00',
                'date_specific' => '2025-12-15',
            ],
        ]);
    }
}
