<?php

namespace App\Policies;

use App\Models\Ingresso;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IngressoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('ingresso_read');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ingresso $ingresso): bool
    {
        return $user->hasPermissionTo('ingresso_read');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('ingresso_create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ingresso $ingresso): bool
    {
        return $user->hasPermissionTo('ingresso_update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ingresso $ingresso): bool
    {
        return $user->hasPermissionTo('ingresso_delete');
    }
}
