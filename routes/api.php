<?php

use AlizHarb\ForgePulse\Http\Controllers\Api\ExecutionApiController;
use AlizHarb\ForgePulse\Http\Controllers\Api\WorkflowApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ForgePulse API Routes
|--------------------------------------------------------------------------
|
| API routes for workflow monitoring and management.
| These routes are prefixed with 'api/forgepulse' by default.
|
*/

Route::prefix('api/forgepulse')
    ->middleware(config('forgepulse.api.middleware', ['api']))
    ->group(function () {
        // Workflow routes
        Route::get('/workflows', [WorkflowApiController::class, 'index'])->name('forgepulse.api.workflows.index');
        Route::get('/workflows/{workflow}', [WorkflowApiController::class, 'show'])->name('forgepulse.api.workflows.show');

        // Execution routes
        Route::get('/executions', [ExecutionApiController::class, 'index'])->name('forgepulse.api.executions.index');
        Route::get('/executions/{execution}', [ExecutionApiController::class, 'show'])->name('forgepulse.api.executions.show');
        Route::post('/executions/{execution}/pause', [ExecutionApiController::class, 'pause'])->name('forgepulse.api.executions.pause');
        Route::post('/executions/{execution}/resume', [ExecutionApiController::class, 'resume'])->name('forgepulse.api.executions.resume');
    });
