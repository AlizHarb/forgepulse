<?php

declare(strict_types=1);

namespace AlizHarb\ForgePulse;

use AlizHarb\ForgePulse\Livewire\WorkflowBuilder;
use AlizHarb\ForgePulse\Livewire\WorkflowExecutionTracker;
use AlizHarb\ForgePulse\Livewire\WorkflowStepEditor;
use AlizHarb\ForgePulse\Livewire\WorkflowTemplateManager;
use AlizHarb\ForgePulse\Livewire\WorkflowVersionHistory;
use AlizHarb\ForgePulse\Models\Workflow;
use AlizHarb\ForgePulse\Policies\WorkflowPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

/**
 * ForgePulse Service Provider
 *
 * Bootstraps the ForgePulse package, registers services, publishes assets,
 * and configures Livewire components.
 */
class ForgePulseServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/forgepulse.php',
            'forgepulse'
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
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'forgepulse');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'forgepulse');

        // Register Livewire components
        $this->registerLivewireComponents();

        // Register policies
        $this->registerPolicies();

        // Register API routes
        $this->registerApiRoutes();

        // Publish package assets
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/forgepulse.php' => config_path('forgepulse.php'),
            ], 'forgepulse-config');

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'forgepulse-migrations');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/forgepulse'),
            ], 'forgepulse-views');

            $this->publishes([
                __DIR__.'/../resources/js' => public_path('vendor/forgepulse/js'),
                __DIR__.'/../resources/css' => public_path('vendor/forgepulse/css'),
            ], 'forgepulse-assets');
        }
    }

    /**
     * Register Livewire components.
     */
    protected function registerLivewireComponents(): void
    {
        if (! class_exists(Livewire::class)) {
            return;
        }

        // Register with dot notation (for forgepulse.workflow-builder usage)
        Livewire::component('forgepulse.workflow-builder', WorkflowBuilder::class);
        Livewire::component('forgepulse.workflow-step-editor', WorkflowStepEditor::class);
        Livewire::component('forgepulse.workflow-execution-tracker', WorkflowExecutionTracker::class);
        Livewire::component('forgepulse.workflow-template-manager', WorkflowTemplateManager::class);
        Livewire::component('forgepulse.workflow-version-history', WorkflowVersionHistory::class);

        // Register with double colon notation (for forgepulse:: usage)
        Livewire::component('forgepulse::workflow-builder', WorkflowBuilder::class);
        Livewire::component('forgepulse::workflow-step-editor', WorkflowStepEditor::class);
        Livewire::component('forgepulse::workflow-execution-tracker', WorkflowExecutionTracker::class);
        Livewire::component('forgepulse::workflow-template-manager', WorkflowTemplateManager::class);
        Livewire::component('forgepulse::workflow-version-history', WorkflowVersionHistory::class);
    }

    /**
     * Register authorization policies.
     */
    protected function registerPolicies(): void
    {
        Gate::policy(Workflow::class, WorkflowPolicy::class);
    }

    /**
     * Register API routes.
     */
    protected function registerApiRoutes(): void
    {
        if (config('forgepulse.api.enabled', true)) {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        }
    }
}
