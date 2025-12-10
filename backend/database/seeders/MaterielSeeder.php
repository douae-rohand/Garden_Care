<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterielSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('materiel')->insert([
            // Gardening equipment
            ['nom_materiel' => 'Tondeuse à gazon', 'created_at' => now(), 'updated_at' => now()],
            ['nom_materiel' => 'Taille-haie électrique', 'created_at' => now(), 'updated_at' => now()],
            ['nom_materiel' => 'Débroussailleuse', 'created_at' => now(), 'updated_at' => now()],
            ['nom_materiel' => 'Râteau et fourche', 'created_at' => now(), 'updated_at' => now()],
            ['nom_materiel' => 'Sécateur professionnel', 'created_at' => now(), 'updated_at' => now()],
            ['nom_materiel' => 'Arrosoir et tuyau', 'created_at' => now(), 'updated_at' => now()],
            // Cleaning equipment
            ['nom_materiel' => 'Aspirateur industriel', 'created_at' => now(), 'updated_at' => now()],
            ['nom_materiel' => 'Nettoyeur vapeur', 'created_at' => now(), 'updated_at' => now()],
            ['nom_materiel' => 'Balai et serpillière', 'created_at' => now(), 'updated_at' => now()],
            ['nom_materiel' => 'Raclette vitres', 'created_at' => now(), 'updated_at' => now()],
            ['nom_materiel' => 'Fer à repasser', 'created_at' => now(), 'updated_at' => now()],
            ['nom_materiel' => 'Produits de nettoyage', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
