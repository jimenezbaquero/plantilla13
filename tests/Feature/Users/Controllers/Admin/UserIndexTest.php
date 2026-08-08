<?php

namespace Tests\Feature\Users\Controllers\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class UserIndexTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_admin_can_view_users_index(): void
    {
        $this->loginAsRole('admin');
        
        $this->get(route('admin.users.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('User/Admin/Index')
            );
    }
    
    public function test_index_returns_users(): void
    {
        User::factory()->count(3)->create();
        
        $this->loginAsRole('admin');
        
        $this->get(route('admin.users.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->has('users')
            );
    }
    
    public function test_index_returns_columns(): void
    {
        $this->loginAsRole('admin');
        
        $this->get(route('admin.users.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->has('columns')
            );
    }
    
    public function test_index_returns_filters(): void
    {
        $this->loginAsRole('admin');
        
        $this->get(route('admin.users.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->has('filters')
            );
    }
    
    public function test_index_returns_actions(): void
    {
        $this->loginAsRole('admin');
        
        $this->get(route('admin.users.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->has('actions')
            );
    }
}