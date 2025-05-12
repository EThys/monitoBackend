<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlanController extends Controller
{
    /**
     * Affiche la liste de tous les plans.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $plans = Plan::all();
        return response()->json([
            'success' => true,
            'message' => 'Liste des plans récupérée avec succès.',
            'data' => $plans
        ]);
    }

    /**
     * Enregistre un nouveau plan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'PlanName' => 'required|string|max:255',
            'PlanDescription' => 'nullable|string',
            'PlanPrice' => 'required|numeric|min:0',
            'PlanFeatures' => 'nullable|array',
            'PlanDurationMonths' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation des données.',
                'errors' => $validator->errors()
            ], 422);
        }

        $plan = Plan::create([
            'PlanName' => $request->PlanName,
            'PlanDescription' => $request->PlanDescription,
            'PlanPrice' => $request->PlanPrice,
            'PlanFeatures' => $request->PlanFeatures,
            'PlanDurationMonths' => $request->PlanDurationMonths,

        ]);

        return response()->json([
            'success' => true,
            'message' => 'Plan créé avec succès.',
            'data' => $plan
        ], 201);
    }

    /**
     * Affiche un plan spécifique.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Plan $plan)
    {
        return response()->json([
            'success' => true,
            'message' => 'Plan récupéré avec succès.',
            'data' => $plan
        ]);
    }

    /**
     * Met à jour un plan existant.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Plan $plan)
    {
        $validator = Validator::make($request->all(), [
            'PlanName' => 'sometimes|string|max:255',
            'PlanDescription' => 'nullable|string',
            'PlanPrice' => 'sometimes|numeric|min:0',
            'PlanFeatures' => 'nullable|array',
            'PlanDurationMonths' => 'sometimes|integer|min:1',
            'IsActive' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation des données.',
                'errors' => $validator->errors()
            ], 422);
        }

        $plan->update([
            'PlanName' => $request->input('PlanName', $plan->PlanName),
            'PlanDescription' => $request->input('PlanDescription', $plan->PlanDescription),
            'PlanPrice' => $request->input('PlanPrice', $plan->PlanPrice),
            'PlanFeatures' => $request->input('PlanFeatures', $plan->PlanFeatures),
            'PlanDurationMonths' => $request->input('PlanDurationMonths', $plan->PlanDurationMonths),
            'IsActive' => $request->input('IsActive', $plan->IsActive)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Plan mis à jour avec succès.',
            'data' => $plan
        ]);
    }

    /**
     * Supprime un plan.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Plan $plan)
    {
        if ($plan->agencies()->count() > 0 || $plan->subscriptions()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer ce plan car il est utilisé par des agences ou des abonnements.'
            ], 409);
        }

        $plan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Plan supprimé avec succès.'
        ]);
    }

    /**
     * Récupère uniquement les plans actifs.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function activePlans()
    {
        $plans = Plan::where('IsActive', true)->get();
        return response()->json([
            'success' => true,
            'message' => 'Liste des plans actifs récupérée avec succès.',
            'data' => $plans
        ]);
    }
}
