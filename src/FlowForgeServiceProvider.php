<?php

declare(strict_types=1);

namespace AlizHarb\FlowForge;

use AlizHarb\FlowForge\Livewire\WorkflowBuilder;
use AlizHarb\FlowForge\Livewire\WorkflowExecutionTracker;
use AlizHarb\FlowForge\Livewire\WorkflowStepEditor;
use AlizHarb\FlowForge\Livewire\WorkflowTemplateManager;
use AlizHarb\FlowForge\Models\Workflow;
use AlizHarb\FlowForge\Policies\WorkflowPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

/**
 * FlowForge Service Provider
 *
 * Bootstraps the FlowForge package, registers services, publishes assets,
 * and configures Livewire components.
 */
class FlowForgeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/flowforge.php',
            'flowforge'
        );

        // Register core services
        $this->app->singleton(Services\WorkflowEngine::class);
        $this->app->singleton(Services\StepExecutor::class);
        $this->app->singleton(Services\ConditionalEvaluator::class);
        $this->app->singleton(Services\WorkflowValidator::class);
        $this->app->singleton(Services\TemplateManager::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'flowforge');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'flowforge');

        // Register Livewire components
        $this->registerLivewireComponents();

        // Register policies
        $this->registerPolicies();

        // Register API routes
        $this->registerApiRoutes();

        // Publish package assets
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/flowforge.php' => config_path('flowforge.php'),
            ], 'flowforge-config');

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'flowforge-migrations');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/flowforge'),
            ], 'flowforge-views');

            $this->publishes([
                __DIR__.'/../resources/js' => public_path('vendor/flowforge/js'),
                __DIR__.'/../resources/css' => public_path('vendor/flowforge/css'),
            ], 'flowforge-assets');
        }
    }

    /**
     * Register Livewire components.
     */
    protected function registerLivewireComponents(): void
    {
        Livewire::component('flowforge.workflow-builder', WorkflowBuilder::class);
        Livewire::component('flowforge.workflow-step-editor', WorkflowStepEditor::class);
        Livewire::component('flowforge.workflow-execution-tracker', WorkflowExecutionTracker::class);
        Livewire::component('flowforge.workflow-template-manager', WorkflowTemplateManager::class);
    }

    /**
     * Register authorization policies.
     */
    protected function registerPolicies(): void
    {
        if (config('flowforge.permissions.enabled', true)) {
            Gate::policy(Workflow::class, WorkflowPolicy::class);
        }
    }

    /**
     * Register API routes.
     */
    protected function registerApiRoutes(): void
    {
        if (config('flowforge.api.enabled', true)) {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        }
    }
}
