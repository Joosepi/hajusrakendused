<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\MyFavoriteSubjectController;
use App\Http\Controllers\SubjectController;

// Test route
Route::get('test', function () {
    return response()->json([
        'message' => 'API is working'
    ]);
});

// Subjects API routes
Route::get('subjects', [SubjectController::class, 'index']);

// Favorite Subjects API routes
Route::prefix('subjects')->group(function () {
    Route::get('/', [SubjectController::class, 'index']);
    Route::get('/{id}', [SubjectController::class, 'show']);
    Route::post('/', [SubjectController::class, 'store'])->middleware('auth:sanctum');
    Route::put('/{id}', [SubjectController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{id}', [SubjectController::class, 'destroy'])->middleware('auth:sanctum');
});
