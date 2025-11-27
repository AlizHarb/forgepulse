<?php

declare(strict_types=1);

namespace AlizHarb\ForgePulse\Services\StepHandlers;

use AlizHarb\ForgePulse\Models\WorkflowStep;
use Illuminate\Support\Facades\Http;

/**
 * Webhook Handler
 *
 * Sends HTTP requests to external webhooks.
 */
class WebhookHandler
{
    /**
     * Handle the step execution.
     *
     * @param  WorkflowStep  $step  The step to execute
     * @param  array<string, mixed>  $context  Execution context
     * @return array<string, mixed> Output data
     */
    public function handle(WorkflowStep $step, array $context): array
    {
        $config = $step->configuration;
        $url = $config['url'] ?? null;
        $method = strtoupper($config['method'] ?? 'POST');
        $headers = $config['headers'] ?? [];
        $payload = $config['payload'] ?? $context;

        if (! $url) {
            throw new \RuntimeException('Webhook URL is required');
        }

        $response = Http::withHeaders($headers)
            ->send($method, $url, ['json' => $payload]);

        return [
            'webhook_sent' => true,
            'status_code' => $response->status(),
            'response_body' => $response->json(),
        ];
    }
}
