<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Intervention;
use App\Models\Commentaire;
use App\Models\Evaluation;
use App\Models\Facture;
use App\Models\Utilisateur;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ========================================================
        // 1. MISE À JOUR DES PRIX DES TÂCHES
        // ========================================================
        
        $this->command->info('📝 Mise à jour des prix des tâches...');
        
        // Prix pour Amina Tazi (ID 5) - Jardinage
        DB::table('intervenant_tache')->where('intervenant_id', 5)->where('tache_id', 1)->update(['prix_tache' => 35]);
        DB::table('intervenant_tache')->where('intervenant_id', 5)->where('tache_id', 2)->update(['prix_tache' => 40]);
        DB::table('intervenant_tache')->where('intervenant_id', 5)->where('tache_id', 4)->update(['prix_tache' => 45]);
        DB::table('intervenant_tache')->where('intervenant_id', 5)->where('tache_id', 5)->update(['prix_tache' => 30]);
        
        // Prix pour Youssef Benslimane (ID 6) - Jardinage
        DB::table('intervenant_tache')->where('intervenant_id', 6)->where('tache_id', 1)->update(['prix_tache' => 38]);
        DB::table('intervenant_tache')->where('intervenant_id', 6)->where('tache_id', 2)->update(['prix_tache' => 42]);
        DB::table('intervenant_tache')->where('intervenant_id', 6)->where('tache_id', 3)->update(['prix_tache' => 35]);
        
        // Prix pour Sara Chraibi (ID 7) - Ménage
        DB::table('intervenant_tache')->where('intervenant_id', 7)->where('tache_id', 6)->update(['prix_tache' => 50]);
        DB::table('intervenant_tache')->where('intervenant_id', 7)->where('tache_id', 7)->update(['prix_tache' => 60]);
        DB::table('intervenant_tache')->where('intervenant_id', 7)->where('tache_id', 8)->update(['prix_tache' => 80]);
        DB::table('intervenant_tache')->where('intervenant_id', 7)->where('tache_id', 9)->update(['prix_tache' => 85]);
        
        // Prix pour Hassan Radi (ID 8) - Ménage
        DB::table('intervenant_tache')->where('intervenant_id', 8)->where('tache_id', 6)->update(['prix_tache' => 65]);
        DB::table('intervenant_tache')->where('intervenant_id', 8)->where('tache_id', 7)->update(['prix_tache' => 55]);
        DB::table('intervenant_tache')->where('intervenant_id', 8)->where('tache_id', 10)->update(['prix_tache' => 45]);
        
        // ========================================================
        // 2. AJOUT DE NOUVELLES INTERVENTIONS
        // ========================================================
        
        $this->command->info('🔧 Ajout de nouvelles interventions...');
        
        $interventions = [
            ['address' => '45 Avenue Hassan II, Rabat', 'ville' => 'Rabat', 'status' => 'terminee', 'date_intervention' => '2025-11-15', 'client_id' => 2, 'intervenant_id' => 5, 'tache_id' => 4],
            ['address' => '78 Boulevard Zerktouni, Casablanca', 'ville' => 'Casablanca', 'status' => 'terminee', 'date_intervention' => '2025-11-20', 'client_id' => 3, 'intervenant_id' => 5, 'tache_id' => 5],
            ['address' => '12 Rue de la Liberté, Marrakech', 'ville' => 'Marrakech', 'status' => 'terminee', 'date_intervention' => '2025-11-10', 'client_id' => 4, 'intervenant_id' => 6, 'tache_id' => 1],
            ['address' => '45 Avenue Hassan II, Rabat', 'ville' => 'Rabat', 'status' => 'terminee', 'date_intervention' => '2025-11-25', 'client_id' => 2, 'intervenant_id' => 6, 'tache_id' => 2],
            ['address' => '78 Boulevard Zerktouni, Casablanca', 'ville' => 'Casablanca', 'status' => 'terminee', 'date_intervention' => '2025-11-12', 'client_id' => 3, 'intervenant_id' => 7, 'tache_id' => 8],
            ['address' => '12 Rue de la Liberté, Marrakech', 'ville' => 'Marrakech', 'status' => 'terminee', 'date_intervention' => '2025-11-18', 'client_id' => 4, 'intervenant_id' => 7, 'tache_id' => 9],
            ['address' => '45 Avenue Hassan II, Rabat', 'ville' => 'Rabat', 'status' => 'terminee', 'date_intervention' => '2025-11-08', 'client_id' => 2, 'intervenant_id' => 8, 'tache_id' => 10],
            ['address' => '78 Boulevard Zerktouni, Casablanca', 'ville' => 'Casablanca', 'status' => 'terminee', 'date_intervention' => '2025-11-22', 'client_id' => 3, 'intervenant_id' => 8, 'tache_id' => 7],
            ['address' => '45 Avenue Hassan II, Rabat', 'ville' => 'Rabat', 'status' => 'terminee', 'date_intervention' => '2025-10-15', 'client_id' => 2, 'intervenant_id' => 6, 'tache_id' => 3],
        ];
        
        $interventionIds = [];
        foreach ($interventions as $intervention) {
            $id = Intervention::create($intervention)->id;
            $interventionIds[] = $id;
        }
        
        // ========================================================
        // 3. AJOUT DE COMMENTAIRES
        // ========================================================
        
        $this->command->info('💬 Ajout de commentaires...');
        
        $commentaires = [
            ['commentaire' => 'Excellent travail sur l\'élagage ! Les arbres sont magnifiques et bien taillés.', 'intervention_id' => $interventionIds[0], 'type_auteur' => 'client'],
            ['commentaire' => 'Intervenant professionnel et très précis. Jardin impeccable !', 'intervention_id' => $interventionIds[1], 'type_auteur' => 'client'],
            ['commentaire' => 'Très satisfait de la tonte, pelouse bien uniforme. Je recommande !', 'intervention_id' => $interventionIds[2], 'type_auteur' => 'client'],
            ['commentaire' => 'Travail soigné sur les haies. Ponctuel et efficace.', 'intervention_id' => $interventionIds[3], 'type_auteur' => 'client'],
            ['commentaire' => 'Nettoyage en profondeur exceptionnel ! La maison brille de propreté.', 'intervention_id' => $interventionIds[4], 'type_auteur' => 'client'],
            ['commentaire' => 'Parfait pour le nettoyage post-travaux. Tout était impeccable !', 'intervention_id' => $interventionIds[5], 'type_auteur' => 'client'],
            ['commentaire' => 'Vitres parfaitement propres, sans traces. Très professionnel.', 'intervention_id' => $interventionIds[6], 'type_auteur' => 'client'],
            ['commentaire' => 'Bon service de ménage, appartement bien nettoyé. Quelques détails à améliorer.', 'intervention_id' => $interventionIds[7], 'type_auteur' => 'client'],
            ['commentaire' => 'Travail correct mais manque de finition. Quelques plantes abîmées.', 'intervention_id' => $interventionIds[8], 'type_auteur' => 'client'],
        ];
        
        foreach ($commentaires as $commentaire) {
            Commentaire::create($commentaire);
        }
        
        // ========================================================
        // 4. AJOUT D'ÉVALUATIONS
        // ========================================================
        
        $this->command->info('⭐ Ajout d\'évaluations...');
        
        $evaluations = [
            // Intervention 0 (Élagage Amina) - Note 5/5
            ['note' => 5, 'intervention_id' => $interventionIds[0], 'critaire_id' => 1, 'type_auteur' => 'client'],
            ['note' => 5, 'intervention_id' => $interventionIds[0], 'critaire_id' => 2, 'type_auteur' => 'client'],
            ['note' => 5, 'intervention_id' => $interventionIds[0], 'critaire_id' => 3, 'type_auteur' => 'client'],
            
            // Intervention 1 (Désherbage Amina) - Note 4/5
            ['note' => 4, 'intervention_id' => $interventionIds[1], 'critaire_id' => 1, 'type_auteur' => 'client'],
            ['note' => 4, 'intervention_id' => $interventionIds[1], 'critaire_id' => 2, 'type_auteur' => 'client'],
            ['note' => 5, 'intervention_id' => $interventionIds[1], 'critaire_id' => 3, 'type_auteur' => 'client'],
            
            // Intervention 2 (Tonte Youssef) - Note 5/5
            ['note' => 5, 'intervention_id' => $interventionIds[2], 'critaire_id' => 1, 'type_auteur' => 'client'],
            ['note' => 5, 'intervention_id' => $interventionIds[2], 'critaire_id' => 2, 'type_auteur' => 'client'],
            ['note' => 5, 'intervention_id' => $interventionIds[2], 'critaire_id' => 3, 'type_auteur' => 'client'],
            
            // Intervention 3 (Taille haies Youssef) - Note 4/5
            ['note' => 4, 'intervention_id' => $interventionIds[3], 'critaire_id' => 1, 'type_auteur' => 'client'],
            ['note' => 4, 'intervention_id' => $interventionIds[3], 'critaire_id' => 2, 'type_auteur' => 'client'],
            ['note' => 4, 'intervention_id' => $interventionIds[3], 'critaire_id' => 3, 'type_auteur' => 'client'],
            
            // Intervention 4 (Deep cleaning Sara) - Note 5/5
            ['note' => 5, 'intervention_id' => $interventionIds[4], 'critaire_id' => 1, 'type_auteur' => 'client'],
            ['note' => 5, 'intervention_id' => $interventionIds[4], 'critaire_id' => 2, 'type_auteur' => 'client'],
            ['note' => 5, 'intervention_id' => $interventionIds[4], 'critaire_id' => 3, 'type_auteur' => 'client'],
            ['note' => 5, 'intervention_id' => $interventionIds[4], 'critaire_id' => 5, 'type_auteur' => 'client'],
            
            // Intervention 5 (Post-travaux Sara) - Note 5/5
            ['note' => 5, 'intervention_id' => $interventionIds[5], 'critaire_id' => 1, 'type_auteur' => 'client'],
            ['note' => 5, 'intervention_id' => $interventionIds[5], 'critaire_id' => 2, 'type_auteur' => 'client'],
            ['note' => 5, 'intervention_id' => $interventionIds[5], 'critaire_id' => 3, 'type_auteur' => 'client'],
            
            // Intervention 6 (Lavage vitres Hassan) - Note 5/5
            ['note' => 5, 'intervention_id' => $interventionIds[6], 'critaire_id' => 1, 'type_auteur' => 'client'],
            ['note' => 5, 'intervention_id' => $interventionIds[6], 'critaire_id' => 2, 'type_auteur' => 'client'],
            ['note' => 5, 'intervention_id' => $interventionIds[6], 'critaire_id' => 3, 'type_auteur' => 'client'],
            
            // Intervention 7 (Ménage Hassan) - Note 3/5
            ['note' => 3, 'intervention_id' => $interventionIds[7], 'critaire_id' => 1, 'type_auteur' => 'client'],
            ['note' => 3, 'intervention_id' => $interventionIds[7], 'critaire_id' => 2, 'type_auteur' => 'client'],
            ['note' => 4, 'intervention_id' => $interventionIds[7], 'critaire_id' => 3, 'type_auteur' => 'client'],
            
            // Intervention 8 (Plantation Youssef) - Note 2/5
            ['note' => 2, 'intervention_id' => $interventionIds[8], 'critaire_id' => 1, 'type_auteur' => 'client'],
            ['note' => 3, 'intervention_id' => $interventionIds[8], 'critaire_id' => 2, 'type_auteur' => 'client'],
            ['note' => 2, 'intervention_id' => $interventionIds[8], 'critaire_id' => 3, 'type_auteur' => 'client'],
        ];
        
        foreach ($evaluations as $evaluation) {
            Evaluation::create($evaluation);
        }
        
        // ========================================================
        // 5. AJOUT DE FACTURES
        // ========================================================
        
        $this->command->info('💰 Ajout de factures...');
        
        $factures = [
            ['fichier_path' => '/uploads/factures/facture_6.pdf', 'ttc' => 360.00, 'intervention_id' => $interventionIds[0]],
            ['fichier_path' => '/uploads/factures/facture_7.pdf', 'ttc' => 240.00, 'intervention_id' => $interventionIds[1]],
            ['fichier_path' => '/uploads/factures/facture_8.pdf', 'ttc' => 304.00, 'intervention_id' => $interventionIds[2]],
            ['fichier_path' => '/uploads/factures/facture_9.pdf', 'ttc' => 336.00, 'intervention_id' => $interventionIds[3]],
            ['fichier_path' => '/uploads/factures/facture_10.pdf', 'ttc' => 640.00, 'intervention_id' => $interventionIds[4]],
            ['fichier_path' => '/uploads/factures/facture_11.pdf', 'ttc' => 680.00, 'intervention_id' => $interventionIds[5]],
            ['fichier_path' => '/uploads/factures/facture_12.pdf', 'ttc' => 360.00, 'intervention_id' => $interventionIds[6]],
            ['fichier_path' => '/uploads/factures/facture_13.pdf', 'ttc' => 440.00, 'intervention_id' => $interventionIds[7]],
            ['fichier_path' => '/uploads/factures/facture_14.pdf', 'ttc' => 280.00, 'intervention_id' => $interventionIds[8]],
        ];
        
        foreach ($factures as $facture) {
            Facture::create($facture);
        }
        
        $this->command->info('');
        $this->command->info('✅ Données de test ajoutées avec succès !');
        $this->command->info('📊 Résumé :');
        $this->command->info('   - 9 nouvelles interventions');
        $this->command->info('   - 9 commentaires d\'avis');
        $this->command->info('   - 27 évaluations (notes variées)');
        $this->command->info('   - 9 factures');
        $this->command->info('   - Prix mis à jour pour toutes les tâches');
    }
}
