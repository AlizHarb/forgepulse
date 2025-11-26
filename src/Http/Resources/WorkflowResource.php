<?php

declare(strict_types=1);

namespace AlizHarb\FlowForge\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Workflow API Resource
 *
 * @mixin \AlizHarb\FlowForge\Models\Workflow
 */
class WorkflowResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status->value,
            'is_template' => $this->is_template,
            'version' => $this->version,
            'steps_count' => $this->whenCounted('steps'),
            'executions_count' => $this->whenCounted('executions'),
            'steps' => StepResource::collection($this->whenLoaded('steps')),
            'recent_executions' => ExecutionResource::collection($this->whenLoaded('executions')),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
