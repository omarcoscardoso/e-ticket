<?php

namespace App\Policies;

use App\Models\Inscrito;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InscritoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('inscrito_read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Inscrito $inscrito): bool
    {
        return $user->hasPermissionTo('inscrito_read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('inscrito_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Inscrito $inscrito): bool
    {
        return $user->hasPermissionTo('inscrito_update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Inscrito $inscrito): bool
    {
        return $user->hasPermissionTo('inscrito_delete');
    }
}
