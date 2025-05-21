<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AgencyController;
use App\Http\Controllers\PlanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//ROUTES POUR L'AUTHENTIFICATION
Route::post('login', [AuthController::class, 'login']);
Route::post('auth/resetPassword', [AuthController::class, 'resetPassword']);




Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('register', [AuthController::class, 'register'])->middleware('role:admin');
    Route::post('store/agency', [AgencyController::class, 'store'])->middleware('role:admin');
    Route::put('put/agency/{agency}', [AgencyController::class, 'update'])->middleware('role:admin');
    Route::delete('delete/agency/{agency}', [AgencyController::class, 'destroy'])->middleware('role:admin');
    Route::post('store/plan', [PlanController::class, 'store'])->middleware('role:admin');
    Route::put('planUpdate/{plan}', [PlanController::class, 'update'])->middleware('role:admin');
    Route::delete('planDestroy/{plan}', [PlanController::class, 'destroy'])->middleware('role:admin');
    Route::post('auth/changePassword', [AuthController::class, 'changePassword'])->middleware('role:admin');

    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('profile', [AuthController::class, 'profile']);
    Route::delete('deleteAccount', [AuthController::class, 'deleteAccount']);

    Route::prefix('plans')->group(function () {
        Route::get('/', [PlanController::class, 'index']);              // GET /api/plans          -> Liste tous les plans
        Route::get('/active', [PlanController::class, 'activePlans']);  // GET /api/plans/active   -> Liste les plans actifs
        Route::get('/{plan}', [PlanController::class, 'show']);         // GET /api/plans/{plan}   -> Affiche un plan spécifique
          // DELETE /api/plans/{plan} -> Supprime un plan
    });
    Route::prefix('agencies')->name('agencies.')->group(function () {
        Route::get('/all', [AgencyController::class, 'index'])->name('index');           // GET /agencie
        Route::get('/show/{agency}', [AgencyController::class, 'show'])->name('show');     // GET /agencies/{agency}
    });

});
