<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FavoriseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('favorise')->insert([
            [
                'client_id' => 2,
                'intervenant_id' => 5,
                'service_id' => 1, // Jardinage
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'client_id' => 3,
                'intervenant_id' => 6,
                'service_id' => 1, // Jardinage
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'client_id' => 4,
                'intervenant_id' => 7,
                'service_id' => 2, // Ménage
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'client_id' => 2,
                'intervenant_id' => 8,
                'service_id' => 2, // Ménage
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
