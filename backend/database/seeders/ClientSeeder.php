<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('client')->insert([
            [
                'id' => 2, // References utilisateur with id=2
                'address' => '45 Avenue Hassan II, Rabat',
                'ville' => 'Rabat',
                'is_active' => true,
                'admin_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3, // References utilisateur with id=3
                'address' => '78 Boulevard Zerktouni, Casablanca',
                'ville' => 'Casablanca',
                'is_active' => true,
                'admin_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4, // References utilisateur with id=4
                'address' => '12 Rue de la Liberté, Marrakech',
                'ville' => 'Marrakech',
                'is_active' => true,
                'admin_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
