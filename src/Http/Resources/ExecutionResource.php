<?php

declare(strict_types=1);

namespace AlizHarb\ForgePulse\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Execution API Resource
 *
 * @mixin \AlizHarb\ForgePulse\Models\WorkflowExecution
 */
class ExecutionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'workflow_id' => $this->workflow_id,
            'workflow' => new WorkflowResource($this->whenLoaded('workflow')),
            'status' => $this->status->value,
            'is_paused' => $this->isPaused(),
            'pause_reason' => $this->pause_reason,
            'context' => $this->context?->getArrayCopy(),
            'output' => $this->output?->getArrayCopy(),
            'error_message' => $this->error_message,
            'retry_count' => $this->retry_count,
            'duration' => $this->duration,
            'started_at' => $this->started_at?->toISOString(),
            'completed_at' => $this->completed_at?->toISOString(),
            'paused_at' => $this->paused_at?->toISOString(),
            'scheduled_at' => $this->scheduled_at?->toISOString(),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
            'logs' => LogResource::collection($this->whenLoaded('logs')),
        ];
    }
}
