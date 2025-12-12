<?php

namespace App\Http\Controllers\Api\Intervention;

use App\Http\Controllers\Controller;
use App\Http\Resources\InterventionResource;
use App\Models\Intervention;
use App\Models\PhotoIntervention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InterventionController extends Controller
{
    /**
     * Display a listing of interventions
     */
    public function index(Request $request)
    {
        $query = Intervention::with([
            'client.utilisateur',
            'intervenant.utilisateur',
            'tache.service',
            'photos',
            'evaluations.critaire',
            'commentaires',
            'facture',
            'materiels',
            'informations'
        ]);

        // Filtrer par statut si fourni
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filtrer par client si fourni
        if ($request->has('clientId')) {
            $query->where('client_id', $request->clientId);
        }

        // Filtrer par intervenant si fourni
        if ($request->has('intervenantId')) {
            $query->where('intervenant_id', $request->intervenantId);
        }

        $interventions = $query->orderBy('date_intervention', 'desc')->paginate(15);

        // Use resource to transform data
        return InterventionResource::collection($interventions);
    }

    /**
     * Store a newly created intervention
     */
    public function store(Request $request)
    {
        \Log::info('Creating intervention with data: ' . json_encode($request->all()));
        try {
            $validated = $request->validate([
                'address' => 'required|string',
                'ville' => 'required|string|max:100',
                'status' => 'nullable|string|max:50',
                'dateIntervention' => 'required|date',
                'clientId' => 'required|exists:client,id',
                'intervenantId' => 'required|exists:intervenant,id',
                'tacheId' => 'required|exists:tache,id',
                'description' => 'nullable|string',
            ]);

            // Map frontend field names to database column names
            $interventionData = [
                'address' => $validated['address'],
                'ville' => $validated['ville'],
                'status' => $validated['status'] ?? 'en_attente',
                'date_intervention' => $validated['dateIntervention'],
                'client_id' => $validated['clientId'],
                'intervenant_id' => $validated['intervenantId'],
                'tache_id' => $validated['tacheId'],
            ];

            \Log::info('Intervention data to create: ' . json_encode($interventionData));
            $intervention = Intervention::create($interventionData);
            \Log::info('Intervention created with ID: ' . $intervention->id);

            // Handle photos if provided
            // Laravel handles photos[] as an array automatically
            if ($request->hasFile('photos')) {
                $photos = $request->file('photos');
                // Handle array of photos (from photos[])
                if (is_array($photos)) {
                    foreach ($photos as $photo) {
                        if ($photo && $photo->isValid()) {
                            $path = $photo->store('interventions', 'public');
                            \App\Models\PhotoIntervention::create([
                                'intervention_id' => $intervention->id,
                                'photo_path' => $path,
                                'phase_prise' => 'avant',
                            ]);
                        }
                    }
                } elseif ($photos && $photos->isValid()) {
                    // Single photo
                    $path = $photos->store('interventions', 'public');
                    \App\Models\PhotoIntervention::create([
                        'intervention_id' => $intervention->id,
                        'photo_path' => $path,
                        'phase_prise' => 'avant',
                    ]);
                }
            }

            // Handle intervention information (surface, etc.)
            if ($request->has('surface') && $request->surface) {
                try {
                    // Find or create information for surface
                    $surfaceInfo = \App\Models\Information::firstOrCreate(
                        ['nom' => 'Surface'],
                        ['nom' => 'Surface', 'description' => 'Surface en m²']
                    );
                    
                    $intervention->informations()->attach($surfaceInfo->id, [
                        'information' => $request->surface . ' m²'
                    ]);
                } catch (\Exception $e) {
                    \Log::warning('Error attaching surface information: ' . $e->getMessage());
                    // Continue without surface info - not critical
                }
            }

            return response()->json([
                'message' => 'Intervention créée avec succès',
                'intervention' => $intervention->load([
                    'client.utilisateur',
                    'intervenant.utilisateur',
                    'tache.service',
                    'photos'
                ]),
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error creating intervention: ' . json_encode($e->errors()));
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating intervention: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile() . ':' . $e->getLine());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('Request data: ' . json_encode($request->all()));
            return response()->json([
                'message' => 'Erreur lors de la création de l\'intervention',
                'error' => config('app.debug') ? $e->getMessage() : 'Une erreur est survenue',
                'file' => config('app.debug') ? $e->getFile() . ':' . $e->getLine() : null
            ], 500);
        }
    }

    /**
     * Display the specified intervention
     */
    public function show($id)
    {
        $intervention = Intervention::with([
            'client.utilisateur',
            'intervenant.utilisateur',
            'tache.service',
            'photos',
            'evaluations.critaire',
            'commentaires',
            'facture',
            'materiels',
            'informations'
        ])->findOrFail($id);

        return new InterventionResource($intervention);
    }

    /**
     * Update the specified intervention
     */
    public function update(Request $request, $id)
    {
        $intervention = Intervention::findOrFail($id);

        $validated = $request->validate([
            'address' => 'sometimes|string',
            'ville' => 'sometimes|string|max:100',
            'status' => 'sometimes|string|max:50',
            'dateIntervention' => 'sometimes|date',
            'clientId' => 'sometimes|exists:client,id',
            'intervenantId' => 'sometimes|exists:intervenant,id',
            'tacheId' => 'sometimes|exists:tache,id',
        ]);

        $intervention->update($validated);

        return response()->json([
            'message' => 'Intervention mise à jour avec succès',
            'intervention' => $intervention->load(['client.utilisateur', 'intervenant.utilisateur', 'tache']),
        ]);
    }

    /**
     * Remove the specified intervention
     */
    public function destroy($id)
    {
        $intervention = Intervention::findOrFail($id);
        $intervention->delete();

        return response()->json([
            'message' => 'Intervention supprimée avec succès',
        ]);
    }

    /**
     * Get upcoming interventions
     */
    public function upcoming()
    {
        $interventions = Intervention::upcoming()
            ->with(['client.utilisateur', 'intervenant.utilisateur', 'tache'])
            ->get();

        return response()->json($interventions);
    }

    /**
     * Get completed interventions
     */
    public function completed()
    {
        $interventions = Intervention::completed()
            ->with(['client.utilisateur', 'intervenant.utilisateur', 'tache'])
            ->orderBy('date_intervention', 'desc')
            ->paginate(15);

        return response()->json($interventions);
    }

    /**
     * Get client interventions with statistics
     */
    public function getClientInterventions(Request $request, $clientId)
    {
        try {
            // Log for debugging
            \Log::info('Fetching interventions for client_id: ' . $clientId);
            
            // First, check if any interventions exist for this client
            $count = Intervention::where('client_id', $clientId)->count();
            \Log::info('Total interventions found for client_id ' . $clientId . ': ' . $count);
            
            $interventions = Intervention::with([
                'client.utilisateur',
                'intervenant.utilisateur',
                'tache.service',
                'photos',
                'evaluations.critaire',
                'commentaires',
                'facture',
                'materiels',
                'informations'
            ])
            ->where('client_id', $clientId)
            ->orderBy('date_intervention', 'desc')
            ->get();

            \Log::info('Loaded ' . $interventions->count() . ' interventions with relationships for client_id: ' . $clientId);

            // Return as array directly for easier frontend consumption
            $data = InterventionResource::collection($interventions)->resolve();
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'count' => count($data)
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching client interventions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des interventions',
                'error' => config('app.debug') ? $e->getMessage() : 'Une erreur est survenue'
            ], 500);
        }
    }

    /**
     * Get client statistics
     */
    public function getClientStatistics($clientId)
    {
        $interventions = Intervention::where('client_id', $clientId)->get();

        $statusMap = [
            'en_attente' => 'pending',
            'en attente' => 'pending',
            'acceptee' => 'accepted',
            'acceptée' => 'accepted',
            'acceptées' => 'accepted',
            'en_cours' => 'in-progress',
            'en cours' => 'in-progress',
            'terminee' => 'completed',
            'terminée' => 'completed',
            'terminées' => 'completed',
            'annulee' => 'cancelled',
            'annulée' => 'cancelled',
            'annulées' => 'cancelled',
            'refusee' => 'rejected',
            'refusée' => 'rejected',
            'refusées' => 'rejected',
            'planifiee' => 'accepted',
            'planifiée' => 'accepted',
        ];

        $stats = [
            'pending' => 0,
            'accepted' => 0,
            'inProgress' => 0,
            'completed' => 0,
            'cancelled' => 0,
            'rejected' => 0,
        ];

        foreach ($interventions as $intervention) {
            $status = strtolower($intervention->status ?? 'en_attente');
            $mappedStatus = $statusMap[$status] ?? 'pending';
            
            if (isset($stats[$mappedStatus])) {
                $stats[$mappedStatus]++;
            }
        }

        return response()->json($stats);
    }

    /**
     * Cancel an intervention
     */
    public function cancelIntervention($id)
    {
        $intervention = Intervention::findOrFail($id);

        // Only allow cancellation if status is pending or accepted
        $allowedStatuses = ['en_attente', 'en attente', 'acceptee', 'acceptée', 'acceptées', 'planifiee', 'planifiée'];
        if (!in_array(strtolower($intervention->status ?? ''), $allowedStatuses)) {
            return response()->json([
                'message' => 'Cette intervention ne peut pas être annulée',
                'error' => 'invalid_status'
            ], 400);
        }

        $intervention->update(['status' => 'annulée']);

        return response()->json([
            'message' => 'Intervention annulée avec succès',
            'intervention' => new InterventionResource($intervention->load([
                'client.utilisateur',
                'intervenant.utilisateur',
                'tache.service',
                'photos',
                'evaluations.critaire',
                'commentaires',
                'facture',
                'materiels',
                'informations'
            ]))
        ]);
    }

    /**
     * Add a photo to an intervention
     */
    public function addPhoto(Request $request, $id)
    {
        $intervention = Intervention::findOrFail($id);

        $validated = $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        // Stocker la photo
        $path = $request->file('photo')->store('interventions', 'public');

        $photo = PhotoIntervention::create([
            'intervention_id' => $intervention->id,
            'photo_path' => $path,
            'description' => $validated['description'] ?? null,
            'phase_prise' => 'apres', // Default to 'apres' (after)
        ]);

        return response()->json([
            'message' => 'Photo ajoutée avec succès',
            'photo' => $photo,
        ], 201);
    }
}
