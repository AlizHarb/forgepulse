<?php

declare(strict_types=1);

namespace AlizHarb\ForgePulse\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Log API Resource
 *
 * @mixin \AlizHarb\ForgePulse\Models\WorkflowExecutionLog
 */
class LogResource extends JsonResource
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
            'step_id' => $this->workflow_step_id,
            'step' => new StepResource($this->whenLoaded('step')),
            'status' => $this->status->value,
            'input' => $this->input?->getArrayCopy(),
            'output' => $this->output?->getArrayCopy(),
            'error_message' => $this->error_message,
            'started_at' => $this->started_at?->toISOString(),
            'completed_at' => $this->completed_at?->toISOString(),
            'created_at' => $this->created_at->toISOString(),
        ];
    }
}
