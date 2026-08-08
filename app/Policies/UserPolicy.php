<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    
    /**
     * Se ejecuta antes que cualquier otro método de la policy.
     */
    public function before(User $user): ?bool
    {
        if ($user->isAdmin) {
            return true;
        }
        
        return null;
    }
    
    /**
     * Determina si el usuario puede ver el listado de usuarios.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }
}
