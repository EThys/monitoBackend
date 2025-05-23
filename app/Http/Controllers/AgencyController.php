<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgencyController extends Controller
{
    /**
     * Affiche la liste de toutes les agences avec leurs plans.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $agencies = Agency::with('plan')->get();

        return response()->json([
            'success' => true,
            'message' => 'Liste des agences récupérée avec succès.',
            'data' => $agencies
        ]);
    }

    /**
     * Crée une nouvelle agence.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validation des données reçues
        $validator = Validator::make($request->all(), [
            'AgencyName' => 'required|string|max:255',
            'AgencyAddress' => 'nullable|string|max:500',
            'AgencyPhone' => 'nullable|string|max:20',
            'AgencyCity' => 'required|string|max:100',
            'AgencyRegion' => 'required|string|max:100',
            'PlanId' => 'required|exists:TPlans,PlanId',
            'AgencyStatus' => 'required|string|in:active,inactive,pending',
            'AgencyStartDate' => 'required|date',
            'AgencyEndDate' => 'required|date|after:AgencyStartDate',
            'AgencyUsed' => 'sometimes|integer|min:0',
            'AgencyDuration' => 'sometimes|integer|min:1'
        ], [
            'PlanId.exists' => 'Le plan sélectionné est invalide.',
            'AgencyStatus.in' => 'Le statut doit être active, inactive ou pending.',
            'AgencyEndDate.after' => 'La date de fin doit être postérieure à la date de début.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation des données.',
                'errors' => $validator->errors()
            ], 422);
        }

        $agency = Agency::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Agence créée avec succès.',
            'data' => $agency
        ], 201);
    }

    /**
     * Affiche une agence spécifique avec son plan.
     *
     * @param  \App\Models\Agency  $agency
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Agency $agency)
    {
        $agency->load('plan');

        return response()->json([
            'success' => true,
            'message' => 'Agence récupérée avec succès.',
            'data' => $agency
        ]);
    }

    /**
     * Met à jour une agence existante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agency  $agency
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Agency $agency)
    {
        $validator = Validator::make($request->all(), [
            'AgencyName' => 'sometimes|string|max:255',
            'AgencyAddress' => 'nullable|string|max:500',
            'AgencyPhone' => 'nullable|string|max:20',
            'AgencyCity' => 'sometimes|string|max:100',
            'AgencyRegion' => 'sometimes|string|max:100',
            'PlanId' => 'sometimes|exists:TPlans,PlanId',
            'AgencyStatus' => 'sometimes|string|in:active,inactive,pending',
            'AgencyStartDate' => 'sometimes|date',
            'AgencyEndDate' => 'sometimes|date|after:AgencyStartDate',
            'AgencyUsed' => 'sometimes|integer|min:0',
            'AgencyDuration' => 'sometimes|integer|min:1'
        ], [
            'PlanId.exists' => 'Le plan sélectionné est invalide.',
            'AgencyStatus.in' => 'Le statut doit être active, inactive ou pending.',
            'AgencyEndDate.after' => 'La date de fin doit être postérieure à la date de début.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation des données.',
                'errors' => $validator->errors()
            ], 422);
        }

        $agency->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Agence mise à jour avec succès.',
            'data' => $agency
        ]);
    }

    /**
     * Supprime une agence si elle n'a pas d'utilisateurs ou abonnements liés.
     *
     * @param  \App\Models\Agency  $agency
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Agency $agency)
    {
        // Vérifie s'il y a des utilisateurs ou abonnements liés
        if ($agency->users()->count() > 0 || $agency->subscriptions()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer cette agence car elle possède des utilisateurs ou abonnements liés.'
            ], 409);
        }

        $agency->delete();

        return response()->json([
            'success' => true,
            'message' => 'Agence supprimée avec succès.'
        ]);
    }
}
