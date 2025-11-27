<?php

declare(strict_types=1);

namespace AlizHarb\ForgePulse\Http\Controllers\Api;

use AlizHarb\ForgePulse\Http\Resources\WorkflowResource;
use AlizHarb\ForgePulse\Models\Workflow;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

/**
 * Workflow API Controller
 *
 * Provides REST API endpoints for workflow management and monitoring.
 */
class WorkflowApiController extends Controller
{
    /**
     * List all workflows.
     */
    public function index(): AnonymousResourceCollection
    {
        $workflows = Workflow::with(['steps', 'executions'])
            ->latest()
            ->paginate(20);

        return WorkflowResource::collection($workflows);
    }

    /**
     * Get a specific workflow.
     */
    public function show(Workflow $workflow): WorkflowResource
    {
        $workflow->load(['steps', 'executions.logs']);

        return new WorkflowResource($workflow);
    }
}
