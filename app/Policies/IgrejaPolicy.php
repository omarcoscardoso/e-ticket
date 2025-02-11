<?php

namespace App\Policies;

use App\Models\Igreja;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IgrejaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('igreja_read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Igreja $igreja): bool
    {
        return $user->hasPermissionTo('igreja_read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('igreja_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Igreja $igreja): bool
    {
        return $user->hasPermissionTo('igreja_update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Igreja $igreja): bool
    {
        return $user->hasPermissionTo('igreja_delete');
    }
}
