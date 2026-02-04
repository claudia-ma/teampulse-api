<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Dashboard summary (mock)
    Route::get('/dashboard/summary', function (Request $request) {
        return response()->json([
            'kpis' => [
                'tasks_total' => 12,
                'tasks_done' => 7,
                'employees' => 4,
            ],
            'recent' => [
                ['title' => 'Revisar nóminas', 'status' => 'pending'],
                ['title' => 'Actualizar horarios', 'status' => 'done'],
            ],
        ]);
    });

    // ✅ Tasks API (protegido por Sanctum)
    Route::get('/tasks', [TaskController::class, 'index']);

    // ✅ IMPORTANTE:
    // Si tu método del controller se llama updateStatus -> lo dejamos así:
    Route::patch('/tasks/{task}', [TaskController::class, 'updateStatus']);

    // (mejora futura PRO: usar Route Model Binding: /tasks/{task} + update(Task $task))
});