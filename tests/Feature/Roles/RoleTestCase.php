<?php

namespace Tests\Feature\Roles;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

abstract class RoleTestCase extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->app
            ->make(PermissionRegistrar::class)
            ->forgetCachedPermissions();
    }
}