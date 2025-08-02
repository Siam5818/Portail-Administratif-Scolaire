<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\BulletinController;
use App\Http\Controllers\TuteurController;
use App\Models\Eleve;
use App\Models\Enseignant;
use App\Models\Matiere;
use App\Models\Tuteur;
use Psy\Command\ListCommand\ClassConstantEnumerator;

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/classes/search', [ClasseController::class, 'search']);
        Route::get('/enseignants/search', [EnseignantController::class, 'search']);
        Route::get('/eleves/search', [EleveController::class, 'search']);
        Route::get('/tuteurs/search', [TuteurController::class, 'search']);
        Route::get('/matieres/search', [MatiereController::class, 'search']);
        Route::get('/notes/search', [NoteController::class, 'search']);
        Route::get('/bulletins/search', [BulletinController::class, 'search']);

        Route::post('/change-password', [AuthController::class, 'changePassword']);

        Route::get('/classes/count', [ClasseController::class, 'count']);
        Route::get('/enseignants/count', [EnseignantController::class, 'count']);
        Route::get('/eleves/count', [EleveController::class, 'count']);
        Route::get('/tuteurs/count', [TuteurController::class, 'count']);
        Route::get('/matieres/count', [MatiereController::class, 'count']);

        Route::apiResource('/classes', ClasseController::class);
        Route::apiResource('/matieres', MatiereController::class);
        Route::apiResource('/notes', NoteController::class);
        Route::apiResource('/bulletins', BulletinController::class);
        Route::apiResource('/enseignants', EnseignantController::class);
        Route::apiResource('/eleves', EleveController::class);
        Route::apiResource('/tuteurs', TuteurController::class);

        Route::post('/logout', [AuthController::class, 'logout']);
    });
});
