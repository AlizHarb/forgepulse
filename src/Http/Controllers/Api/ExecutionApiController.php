<?php

declare(strict_types=1);

namespace AlizHarb\ForgePulse\Http\Controllers\Api;

use AlizHarb\ForgePulse\Http\Resources\ExecutionResource;
use AlizHarb\ForgePulse\Models\WorkflowExecution;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

/**
 * Execution API Controller
 *
 * Provides REST API endpoints for monitoring workflow executions.
 */
class ExecutionApiController extends Controller
{
    /**
     * List all executions.
     */
    public function index(): AnonymousResourceCollection
    {
        $executions = WorkflowExecution::with(['workflow', 'logs'])
            ->latest()
            ->paginate(20);

        return ExecutionResource::collection($executions);
    }

    /**
     * Get a specific execution.
     */
    public function show(WorkflowExecution $execution): ExecutionResource
    {
        $execution->load(['workflow', 'logs.step']);

        return new ExecutionResource($execution);
    }

    /**
     * Pause an execution.
     */
    public function pause(WorkflowExecution $execution): ExecutionResource
    {
        $execution->pause(request('reason'));

        return new ExecutionResource($execution);
    }

    /**
     * Resume an execution.
     */
    public function resume(WorkflowExecution $execution): ExecutionResource
    {
        $execution->resume();

        return new ExecutionResource($execution);
    }
}
