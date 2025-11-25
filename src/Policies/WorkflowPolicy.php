<?php

declare(strict_types=1);

namespace AlizHarb\FlowForge\Policies;

use AlizHarb\FlowForge\Models\Workflow;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Workflow Policy
 *
 * Handles authorization for workflow actions based on roles and ownership.
 */
class WorkflowPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any workflows.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     */
    public function viewAny($user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the workflow.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     */
    public function view($user, Workflow $workflow): bool
    {
        if (! config('flowforge.permissions.enabled', true)) {
            return true;
        }

        // Users can view their own workflows
        if ($workflow->user_id === $user->getAuthIdentifier()) {
            return true;
        }

        // Team-based access
        if (config('flowforge.permissions.team_based', false) && $workflow->team_id) {
            return method_exists($user, 'belongsToTeam') && $user->belongsToTeam($workflow->team_id);
        }

        // Templates are viewable by all
        if ($workflow->is_template) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create workflows.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     */
    public function create($user): bool
    {
        if (! config('flowforge.permissions.enabled', true)) {
            return true;
        }

        /** @var array<string> $allowedRoles */
        $allowedRoles = config('flowforge.permissions.can_create', ['admin', 'workflow-manager']);

        return $this->userHasRole($user, $allowedRoles);
    }

    /**
     * Determine whether the user can update the workflow.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     */
    public function update($user, Workflow $workflow): bool
    {
        if (! config('flowforge.permissions.enabled', true)) {
            return true;
        }

        // Users can update their own workflows
        if ($workflow->user_id === $user->getAuthIdentifier()) {
            return true;
        }

        // Team-based access
        if (config('flowforge.permissions.team_based', false) && $workflow->team_id) {
            return method_exists($user, 'belongsToTeam') && $user->belongsToTeam($workflow->team_id);
        }

        return false;
    }

    // ... (skip delete and execute)

    /**
     * Check if user has any of the specified roles.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array<string>  $roles
     */
    protected function userHasRole($user, array $roles): bool
    {
        if (! method_exists($user, 'hasRole')) {
            // Fallback: assume user has permission if no role system
            return true;
        }

        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return true;
            }
        }

        return false;
    }
}
