<?php

use AlizHarb\FlowForge\Http\Controllers\Api\ExecutionApiController;
use AlizHarb\FlowForge\Http\Controllers\Api\WorkflowApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| FlowForge API Routes
|--------------------------------------------------------------------------
|
| API routes for workflow monitoring and management.
| These routes are prefixed with 'api/flowforge' by default.
|
*/

Route::prefix('api/flowforge')
    ->middleware(config('flowforge.api.middleware', ['api']))
    ->group(function () {
        // Workflow routes
        Route::get('/workflows', [WorkflowApiController::class, 'index'])->name('flowforge.api.workflows.index');
        Route::get('/workflows/{workflow}', [WorkflowApiController::class, 'show'])->name('flowforge.api.workflows.show');

        // Execution routes
        Route::get('/executions', [ExecutionApiController::class, 'index'])->name('flowforge.api.executions.index');
        Route::get('/executions/{execution}', [ExecutionApiController::class, 'show'])->name('flowforge.api.executions.show');
        Route::post('/executions/{execution}/pause', [ExecutionApiController::class, 'pause'])->name('flowforge.api.executions.pause');
        Route::post('/executions/{execution}/resume', [ExecutionApiController::class, 'resume'])->name('flowforge.api.executions.resume');
    });
