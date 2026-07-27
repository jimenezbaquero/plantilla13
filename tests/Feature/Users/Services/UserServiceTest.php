<?php

namespace Tests\Feature\Users\Services;

use App\Models\User;
use App\Presenters\UserPresenter;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;
    
    private UserService $service;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->service = app(UserService::class);
    }
    
    public function test_returns_paginated_users(): void
    {
        User::factory()->count(5)->create();
        
        $result = $this->service->getData([]);
        
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }
    
    public function test_returns_default_pagination(): void
    {
        User::factory()->count(15)->create();
        
        $result = $this->service->getData([]);
        
        $this->assertCount(10, $result->items());
        $this->assertEquals(15, $result->total());
    }
    
    public function test_returns_requested_per_page(): void
    {
        User::factory()->count(15)->create();
        
        $result = $this->service->getData([
            'perPage' => 5,
        ]);
        
        $this->assertCount(5, $result->items());
    }
    
    public function test_returns_requested_page(): void
    {
        User::factory()->count(15)->create();
        
        $result = $this->service->getData([
            'page' => 2,
        ]);
        
        $this->assertEquals(2, $result->currentPage());
    }
    
    public function test_returns_transformed_users(): void
    {
        User::factory()->create();
        
        $result = $this->service->getData([]);
        
        $user = $result->items()[0];
        
        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('name', $user);
        $this->assertArrayHasKey('role', $user);
        $this->assertCount(3, $user);
    }
    
    public function test_returns_role_name(): void
    {
        $user = User::factory()->create();
        
        Role::findOrCreate('admin');
        
        $user->assignRole('admin');
        
        $result = $this->service->getData([]);
        
        $this->assertEquals('admin', $result->items()[0]['role']);
    }
    
    public function test_returns_sin_rol_when_user_has_no_role(): void
    {
        User::factory()->create();
        
        $result = $this->service->getData([]);
        
        $this->assertEquals('Sin rol', $result->items()[0]['role']);
    }
    
    public function test_applies_filters(): void
    {
        User::factory()->create([
            'name' => 'Manuel',
        ]);
        
        User::factory()->create([
            'name' => 'Pedro',
        ]);
        
        $filters = UserPresenter::filters();
        $filters['name']['value'] = 'Manuel';
        
        $result = $this->service->getData($filters);
        
        $this->assertCount(1, $result->items());
        $this->assertEquals('Manuel', $result->items()[0]['name']);
    }
    
    public function test_applies_order(): void
    {
        User::factory()->create([
            'name' => 'Zeta',
        ]);
        
        User::factory()->create([
            'name' => 'Alfa',
        ]);
        
        $filters = UserPresenter::filters();
        $filters['name']['order_direction'] = 'asc';
        
        $result = $this->service->getData($filters);
        
        $this->assertEquals('Alfa', $result->items()[0]['name']);
        $this->assertEquals('Zeta', $result->items()[1]['name']);
    }
}