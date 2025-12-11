<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Intervenant;
use Illuminate\Http\Request;

class IntervenantController extends Controller
{
    /**
     * Get the active services and tasks for an intervenant
     */
    public function getActiveServicesAndTasks($intervenantId)
    {
        $intervenant = Intervenant::with(['services', 'taches'])->find($intervenantId);

        if (!$intervenant) {
            return response()->json(['error' => 'Intervenant not found'], 404);
        }

        // Get active services
        $activeServices = $intervenant->services()
            ->wherePivot('status', 'active')
            ->get()
            ->map(function ($service) {
                return [
                    'id' => $service->id,
                    'name' => $service->nom_service,
                    'status' => $service->pivot->status,
                ];
            });

        // Get active tasks
        $activeTasks = $intervenant->taches()
            ->wherePivot('status', 1)  // status = 1 means active
            ->get()
            ->map(function ($tache) {
                return [
                    'id' => $tache->id,
                    'name' => $tache->nom_tache,
                    'price' => $tache->pivot->prix_tache,
                    'status' => $tache->pivot->status,
                ];
            });

        return response()->json([
            'services' => $activeServices,
            'tasks' => $activeTasks,
        ]);
    }
}
