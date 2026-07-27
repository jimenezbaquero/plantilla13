<?php

namespace Tests\Helpers;

use App\Models\User;
use Spatie\Permission\Models\Role;

trait AuthenticationHelper
{
    protected function loginAsRole(string $role): User
    {
        $user = User::factory()->create();
        
        Role::findOrCreate($role);
        
        $user->assignRole($role);
        
        $this->actingAs($user);
        
        return $user;
    }
}