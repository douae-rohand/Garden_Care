<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of clients
     */
    public function index(Request $request)
    {
        $query = Client::with(['utilisateur', 'admin.utilisateur']);

        // Filtrer les clients actifs uniquement si demandé
        if ($request->has('active') && $request->active == 'true') {
            $query->active();
        }

        $clients = $query->get();

        return response()->json($clients);
    }

    /**
     * Store a newly created client
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:utilisateur,id',
            'address' => 'nullable|string',
            'ville' => 'nullable|string|max:100',
            'isActive' => 'nullable|boolean',
            'adminId' => 'nullable|exists:admin,id',
        ]);

        $client = Client::create($validated);

        return response()->json([
            'message' => 'Client créé avec succès',
            'client' => $client->load('utilisateur'),
        ], 201);
    }

    /**
     * Display the specified client
     */
    public function show($id)
    {
        $client = Client::with(['utilisateur', 'admin.utilisateur', 'interventions', 'intervenantsFavoris.utilisateur'])
            ->findOrFail($id);

        return response()->json($client);
    }

    /**
     * Update the specified client
     */
    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $validated = $request->validate([
            'address' => 'nullable|string',
            'ville' => 'nullable|string|max:100',
            'isActive' => 'nullable|boolean',
            'adminId' => 'nullable|exists:admin,id',
        ]);

        $client->update($validated);

        return response()->json([
            'message' => 'Client mis à jour avec succès',
            'client' => $client->load('utilisateur'),
        ]);
    }

    /**
     * Remove the specified client
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return response()->json([
            'message' => 'Client supprimé avec succès',
        ]);
    }

    /**
     * Get interventions for a specific client
     */
    public function interventions($id)
    {
        $client = Client::findOrFail($id);
        $interventions = $client->interventions()
            ->with(['intervenant.utilisateur', 'tache'])
            ->orderBy('dateIntervention', 'desc')
            ->get();

        return response()->json($interventions);
    }

    /**
     * Add an intervenant to favorites
     */
    public function addFavorite(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $validated = $request->validate([
            'intervenantId' => 'required|exists:intervenant,id',
        ]);

        $client->intervenantsFavoris()->syncWithoutDetaching([$validated['intervenantId']]);

        return response()->json([
            'message' => 'Intervenant ajouté aux favoris',
        ]);
    }

    /**
     * Remove an intervenant from favorites
     */
    public function removeFavorite($id, $intervenantId)
    {
        $client = Client::findOrFail($id);
        $client->intervenantsFavoris()->detach($intervenantId);

        return response()->json([
            'message' => 'Intervenant retiré des favoris',
        ]);
    }

    /**
     * Get client favorites with detailed information
     */
    public function getFavorites($id)
    {
        $client = Client::findOrFail($id);
        
        $favorites = $client->intervenantsFavoris()
            ->with([
                'utilisateur',
                'services',
                'taches.service',
                'interventions' => function($query) use ($id) {
                    $query->where('client_id', $id);
                }
            ])
            ->get();

        // Transform data for frontend
        $favoritesData = $favorites->map(function($intervenant) use ($id) {
            $clientInterventions = $intervenant->interventions;
            $lastIntervention = $clientInterventions->sortByDesc('date_intervention')->first();
            
            // Calculate average rating
            $evaluations = \App\Models\Evaluation::whereHas('intervention', function($query) use ($intervenant) {
                $query->where('intervenant_id', $intervenant->id);
            })->where('type_auteur', 'client')->get();
            
            $averageRating = null;
            $totalReviews = 0;
            if ($evaluations->count() > 0) {
                $totalReviews = $evaluations->count();
                $averageRating = round($evaluations->avg('note'), 1);
            }

            // Get services offered
            $services = $intervenant->services->pluck('nom_service')->toArray();
            
            // Get hourly rate (from intervenant_tache)
            $hourlyRate = null;
            if ($intervenant->taches->count() > 0) {
                $firstTache = $intervenant->taches->first();
                $intervenantTache = \App\Models\IntervenantTache::where('intervenant_id', $intervenant->id)
                    ->where('tache_id', $firstTache->id)
                    ->first();
                $hourlyRate = $intervenantTache ? $intervenantTache->prix_tache : null;
            }

            return [
                'id' => $intervenant->id,
                'name' => trim(($intervenant->utilisateur->prenom ?? '') . ' ' . ($intervenant->utilisateur->nom ?? '')),
                'image' => $intervenant->utilisateur->url ?? 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop',
                'phone' => $intervenant->utilisateur->telephone ?? '',
                'location' => $intervenant->ville ?? '',
                'averageRating' => $averageRating,
                'totalReviews' => $totalReviews,
                'services' => $services,
                'hourlyRate' => $hourlyRate,
                'servicesWithClient' => $clientInterventions->count(),
                'totalMissions' => \App\Models\Intervention::where('intervenant_id', $intervenant->id)->count(),
                'lastServiceDate' => $lastIntervention ? $lastIntervention->date_intervention : null,
            ];
        });

        return response()->json([
            'data' => $favoritesData
        ]);
    }
}
