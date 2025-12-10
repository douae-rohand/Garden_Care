<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CritaireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('critaire')->insert([
            ['nom_critaire' => 'Qualité du travail', 'created_at' => now(), 'updated_at' => now()],
            ['nom_critaire' => 'Ponctualité', 'created_at' => now(), 'updated_at' => now()],
            ['nom_critaire' => 'Professionnalisme', 'created_at' => now(), 'updated_at' => now()],
            ['nom_critaire' => 'Communication', 'created_at' => now(), 'updated_at' => now()],
            ['nom_critaire' => 'Propreté', 'created_at' => now(), 'updated_at' => now()],
            ['nom_critaire' => 'Rapport qualité/prix', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
