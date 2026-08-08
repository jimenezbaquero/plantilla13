<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Helpers\AuthenticationHelper;

abstract class TestCase extends BaseTestCase
{
    use AuthenticationHelper;
}
