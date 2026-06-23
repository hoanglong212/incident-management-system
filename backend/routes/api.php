<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\IncidentController;

Route::post('/register', [AuthController::class, 'register'])
    ->middleware('throttle:login');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
Route::middleware(['auth:sanctum', 'role:ADMIN'])->get('/admin/test', function (Request $request) {
    return response()->json([
        'message' => 'Admin route accessed successfully',
        'user' => $request->user()->load('role'),
    ]);
});
Route::middleware(['auth:sanctum', 'role:MANAGER'])->get('/manager/test', function (Request $request) {
    return response()->json([
        'message' => 'Manager route accessed successfully',
        'user' => $request->user()->load('role'),
    ]);

});
Route::middleware(['auth:sanctum', 'role:USER|ADMIN'])->post('/incidents', [IncidentController::class, 'store']);
Route::middleware(['auth:sanctum', 'role:USER|ADMIN|MANAGER|TECHNICIAN'])->get('/incidents', [IncidentController::class, 'index']);
Route::middleware(['auth:sanctum', 'role:USER|ADMIN|MANAGER|TECHNICIAN'])->get('/incidents/{id}', [IncidentController::class, 'show']);
}); 