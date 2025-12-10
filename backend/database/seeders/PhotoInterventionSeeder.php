<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhotoInterventionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('photo_intervention')->insert([
            [
                'intervention_id' => 1,
                'photo_path' => '/uploads/photos/intervention_1_before.jpg',
                'description' => 'Avant réparation de la fuite',
                'phase_prise' => 'avant',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'intervention_id' => 1,
                'photo_path' => '/uploads/photos/intervention_1_after.jpg',
                'description' => 'Après réparation de la fuite',
                'phase_prise' => 'apres',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'intervention_id' => 2,
                'photo_path' => '/uploads/photos/intervention_2_tableau.jpg',
                'description' => 'Nouveau tableau électrique installé',
                'phase_prise' => 'apres',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'intervention_id' => 3,
                'photo_path' => '/uploads/photos/intervention_3_jardin.jpg',
                'description' => 'Pelouse bien tondue',
                'phase_prise' => 'avant',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
