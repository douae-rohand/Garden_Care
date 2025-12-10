<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceInformationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('service_information')->insert([
            // Jardinage
            ['service_id' => 1, 'information_id' => 1], // Surface à traiter
            ['service_id' => 1, 'information_id' => 4], // Accès difficile
            ['service_id' => 1, 'information_id' => 5], // Urgence
            // Ménage
            ['service_id' => 2, 'information_id' => 1], // Surface
            ['service_id' => 2, 'information_id' => 2], // Nombre de pièces
            ['service_id' => 2, 'information_id' => 5], // Urgence
            ['service_id' => 2, 'information_id' => 6], // Étage
        ]);
    }
}
