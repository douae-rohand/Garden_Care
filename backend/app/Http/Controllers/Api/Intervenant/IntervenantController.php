<?php

namespace App\Http\Controllers\Api\Intervenant;

use App\Http\Controllers\Controller;
use App\Models\Intervenant;
use Illuminate\Http\Request;

class IntervenantController extends Controller
{
    /**
     * Display a listing of intervenants
     */
    public function index(Request $request)
    {
        $query = Intervenant::with(['utilisateur', 'admin.utilisateur', 'taches']);

        // Filtrer les intervenants actifs uniquement si demandé
        if ($request->has('active') && $request->active == 'true') {
            $query->active();
        }

        // Filtrer par tâche spécifique (intervenants pouvant effectuer une tâche)
        if ($request->has('tacheId')) {
            $query->whereHas('taches', function ($q) use ($request) {
                $q->where('tache.id', $request->tacheId);
            });
        }

        $intervenants = $query->get();

        return response()->json($intervenants);
    }

    /**
     * Store a newly created intervenant
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:utilisateur,id',
            'address' => 'nullable|string',
            'ville' => 'nullable|string|max:100',
            'bio' => 'nullable|string',
            'isActive' => 'nullable|boolean',
            'adminId' => 'nullable|exists:admin,id',
        ]);

        $intervenant = Intervenant::create($validated);

        return response()->json([
            'message' => 'Intervenant créé avec succès',
            'intervenant' => $intervenant->load('utilisateur'),
        ], 201);
    }

    /**
     * Display the specified intervenant
     */
    public function show($id)
    {
        $intervenant = Intervenant::with([
            'utilisateur',
            'admin.utilisateur',
            'interventions',
            'disponibilites',
            'taches',
            'materiels',
            'clientsFavoris.utilisateur'
        ])->findOrFail($id);

        return response()->json($intervenant);
    }

    /**
     * Update the specified intervenant
     */
    public function update(Request $request, $id)
    {
        $intervenant = Intervenant::findOrFail($id);

        $validated = $request->validate([
            'address' => 'nullable|string',
            'ville' => 'nullable|string|max:100',
            'bio' => 'nullable|string',
            'isActive' => 'nullable|boolean',
            'adminId' => 'nullable|exists:admin,id',
        ]);

        $intervenant->update($validated);

        return response()->json([
            'message' => 'Intervenant mis à jour avec succès',
            'intervenant' => $intervenant->load('utilisateur'),
        ]);
    }

    /**
     * Remove the specified intervenant
     */
    public function destroy($id)
    {
        $intervenant = Intervenant::findOrFail($id);
        $intervenant->delete();

        return response()->json([
            'message' => 'Intervenant supprimé avec succès',
        ]);
    }

    /**
     * Get interventions for a specific intervenant
     */
    public function interventions($id)
    {
        $intervenant = Intervenant::findOrFail($id);
        $interventions = $intervenant->interventions()
            ->with(['client.utilisateur', 'tache'])
            ->orderBy('dateIntervention', 'desc')
            ->get();

        return response()->json($interventions);
    }

    /**
     * Get disponibilites for a specific intervenant
     */
    public function disponibilites($id)
    {
        $intervenant = Intervenant::findOrFail($id);
        $disponibilites = $intervenant->disponibilites()
            ->orderBy('dateDebut', 'asc')
            ->get();

        return response()->json($disponibilites);
    }

    /**
     * Get taches that this intervenant can perform
     */
    public function taches($id)
    {
        $intervenant = Intervenant::findOrFail($id);
        $taches = $intervenant->taches()
            ->with('service')
            ->get();

        return response()->json($taches);
    }

    /**
     * Get services that this intervenant can perform
     * Returns only services where the intervenant has at least one task
     */
    public function services($id)
    {
        $intervenant = Intervenant::findOrFail($id);
        
        \Log::info('Fetching services for intervenant ID: ' . $id);
        
        // Get all services
        $allServices = \App\Models\Service::all();
        
        // Filter services: only include services where intervenant has at least one task
        $servicesData = $allServices->map(function($service) use ($intervenant) {
            // Count tasks that this intervenant can perform for this service
            $tachesCount = \App\Models\Tache::where('service_id', $service->id)
                ->whereHas('intervenants', function($query) use ($intervenant) {
                    $query->where('intervenant_id', $intervenant->id);
                })
                ->count();

            // Only include service if intervenant has at least one task
            if ($tachesCount > 0) {
                \Log::info("Service: {$service->nom_service}, Tasks count: {$tachesCount}");
                return [
                    'id' => $service->id,
                    'nom_service' => $service->nom_service,
                    'description' => $service->description,
                    'taches_count' => $tachesCount
                ];
            }
            
            return null;
        })->filter(); // Remove null values

        \Log::info('Found ' . $servicesData->count() . ' services for intervenant');

        return response()->json([
            'data' => $servicesData->values() // Re-index array
        ]);
    }
}
