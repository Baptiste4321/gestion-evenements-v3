<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

/*
// Route pour se connecter et obtenir un token
Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');
    // Vérification des identifiants
    if (!auth()->attempt($credentials)) {
    return response()->json(['message' => 'Invalid credentials'], 401);
    }
    // Génération d'un token
    $token = $request->user()->createToken('API Token')->plainTextToken;
    return response()->json(['token' => $token]);
    });
    // Routes protégées nécessitant un token
    Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', function (Request $request) {
    return $request->user();
    });
    });

*/

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;

// Routes d'authentification
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Routes des événements avec protection
Route::middleware('auth:sanctum')->group(function () {
    Route::post('events', [EventController::class, 'store']);  // Créer un événement
    Route::put('events/{id}', [EventController::class, 'update']);  // Mettre à jour un événement
    Route::delete('events/{id}', [EventController::class, 'destroy']);  // Supprimer un événement
});

// Routes publiques pour voir les événements
Route::get('events', [EventController::class, 'index']);  // Liste des événements
Route::get('events/{id}', [EventController::class, 'show']);  // Voir un événement spécifique

Route::get('/events/{slug}/{id}', [EventController::class, 'show']);
