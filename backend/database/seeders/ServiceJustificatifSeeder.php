<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceJustificatifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('service_justificatif')->insert([
            // Jardinage - professional certifications
            ['service_id' => 1, 'justificatif_id' => 1],
            ['service_id' => 1, 'justificatif_id' => 2],
            // Ménage - professional certifications
            ['service_id' => 2, 'justificatif_id' => 3],
            ['service_id' => 2, 'justificatif_id' => 4],
            ['service_id' => 2, 'justificatif_id' => 5],
            ['service_id' => 2, 'justificatif_id' => 6],
        ]);
    }
}
