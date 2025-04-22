<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\MyFavoriteSubjectController;

// Test route
Route::get('test', function () {
    return response()->json([
        'message' => 'API is working'
    ]);
});

// Subjects route
Route::get('subjects', function () {
    return response()->json([
        'message' => 'Subjects API endpoint',
        'time' => now()->toDateTimeString()
    ]);
});
