<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('service')->insert([
            [
                'nom_service' => 'Jardinage',
                'description' => 'Entretien de jardins, taille, tonte, arrosage, débroussaillage et aménagement paysager.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom_service' => 'Ménage',
                'description' => 'Services de nettoyage et ménage résidentiels et commerciaux, entretien régulier et nettoyage en profondeur.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
