<?php

declare(strict_types=1);

namespace AlizHarb\ForgePulse\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Step API Resource
 *
 * @mixin \AlizHarb\ForgePulse\Models\WorkflowStep
 */
class StepResource extends JsonResource
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
            'type' => $this->type->value,
            'position' => $this->position,
            'is_enabled' => $this->is_enabled,
            'timeout' => $this->timeout,
            'execution_mode' => $this->execution_mode ?? 'sequential',
            'parallel_group' => $this->parallel_group,
            'configuration' => $this->configuration->getArrayCopy(),
            'conditions' => $this->conditions?->getArrayCopy(),
        ];
    }
}
