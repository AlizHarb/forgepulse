<?php

declare(strict_types=1);

namespace AlizHarb\FlowForge\Services;

use AlizHarb\FlowForge\Models\Workflow;
use Illuminate\Support\Facades\Storage;

/**
 * Template Manager Service
 *
 * Manages workflow templates including creation, instantiation,
 * import/export, and versioning.
 */
class TemplateManager
{
    /**
     * Export a workflow as a template file.
     *
     * @param  Workflow  $workflow  The workflow to export
     * @param  string|null  $filename  Optional filename
     * @return string Path to exported file
     */
    public function export(Workflow $workflow, ?string $filename = null): string
    {
        $disk = Storage::disk(config('flowforge.templates.disk', 'local'));
        $directory = config('flowforge.templates.directory', 'workflow-templates');

        $filename = $filename ?? $this->generateFilename($workflow);
        $path = "{$directory}/{$filename}";

        $data = [
            'name' => $workflow->name,
            'description' => $workflow->description,
            'version' => $workflow->version,
            'configuration' => $workflow->configuration,
            'exported_at' => now()->toIso8601String(),
            'steps' => $workflow->steps->map(function ($step) {
                return [
                    'name' => $step->name,
                    'description' => $step->description,
                    'type' => $step->type,
                    'configuration' => $step->configuration,
                    'conditions' => $step->conditions,
                    'position' => $step->position,
                    'x_position' => $step->x_position,
                    'y_position' => $step->y_position,
                    'parent_position' => $step->parent ? $step->parent->position : null,
                ];
            })->toArray(),
        ];

        $disk->put($path, (string) json_encode($data, JSON_PRETTY_PRINT));

        return $path;
    }

    /**
     * Import a workflow from a template file.
     *
     * @param  string  $path  Path to template file
     * @param  string|null  $name  Optional workflow name
     * @return Workflow The created workflow
     */
    public function import(string $path, ?string $name = null): Workflow
    {
        $disk = Storage::disk(config('flowforge.templates.disk', 'local'));

        if (! $disk->exists($path)) {
            throw new \InvalidArgumentException("Template file not found: {$path}");
        }

        $content = $disk->get($path);

        if (! $content) {
            throw new \InvalidArgumentException("Template file is empty: {$path}");
        }

        $data = json_decode($content, true);

        if (! $data) {
            throw new \InvalidArgumentException('Invalid template file format');
        }

        $workflow = Workflow::create([
            'name' => $name ?? $data['name'],
            'description' => $data['description'] ?? null,
            'configuration' => $data['configuration'] ?? null,
            'version' => $data['version'] ?? '1.0.0',
            'status' => 'draft',
            'user_id' => auth()->id(),
        ]);

        $stepMap = []; // Map old positions to new step IDs

        foreach ($data['steps'] as $stepData) {
            $step = $workflow->steps()->create([
                'name' => $stepData['name'],
                'description' => $stepData['description'] ?? null,
                'type' => $stepData['type'],
                'configuration' => $stepData['configuration'],
                'conditions' => $stepData['conditions'] ?? null,
                'position' => $stepData['position'],
                'x_position' => $stepData['x_position'] ?? null,
                'y_position' => $stepData['y_position'] ?? null,
            ]);

            $stepMap[$stepData['position']] = $step->id;
        }

        // Update parent relationships
        foreach ($data['steps'] as $stepData) {
            if (isset($stepData['parent_position'])) {
                $step = $workflow->steps()->where('position', $stepData['position'])->first();

                if ($step) {
                    $step->update([
                        'parent_step_id' => $stepMap[$stepData['parent_position']] ?? null,
                    ]);
                }
            }
        }

        return $workflow;
    }

    /**
     * List available templates.
     *
     * @return array<int, array<string, mixed>> List of template files
     */
    public function listTemplates(): array
    {
        $disk = Storage::disk(config('flowforge.templates.disk', 'local'));
        $directory = config('flowforge.templates.directory', 'workflow-templates');

        $files = $disk->files($directory);

        return collect($files)->map(function ($file) use ($disk) {
            $content = $disk->get($file);
            $data = $content ? json_decode($content, true) : [];

            return [
                'path' => $file,
                'name' => $data['name'] ?? basename($file, '.json'),
                'description' => $data['description'] ?? null,
                'version' => $data['version'] ?? null,
                'exported_at' => $data['exported_at'] ?? null,
            ];
        })->toArray();
    }

    /**
     * Generate a filename for a workflow export.
     *
     * @param  Workflow  $workflow  The workflow
     * @return string Generated filename
     */
    protected function generateFilename(Workflow $workflow): string
    {
        $slug = \Illuminate\Support\Str::slug($workflow->name);
        $timestamp = now()->format('Y-m-d-His');

        return "{$slug}-{$timestamp}.json";
    }
}
