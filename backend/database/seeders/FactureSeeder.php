<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FactureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('facture')->insert([
            [
                'intervention_id' => 1,
                'fichier_path' => '/uploads/factures/facture_1.pdf',
                'ttc' => 450.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'intervention_id' => 2,
                'fichier_path' => '/uploads/factures/facture_2.pdf',
                'ttc' => 850.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
