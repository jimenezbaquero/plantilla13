<?php

namespace Tests\Feature\Roles;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\Helpers\AuthenticationHelper;
use Tests\TestCase;

class RoleAuthorizationTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->app
            ->make(PermissionRegistrar::class)
            ->forgetCachedPermissions();
    }
    
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.users.index'));
        
        $response->assertRedirect(route('login'));
    }
    
    public function test_user_without_required_role_cannot_access_route(): void
    {
        $this->loginAsRole('user');
        
        $this->get(route('admin.users.index'))
            ->assertForbidden();
    }
    
    public function test_user_with_required_role_can_access_route(): void
    {
        $this->loginAsRole('admin');
        
        $this->get(route('admin.users.index'))
            ->assertOk();
    }
    
}