<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_boots_and_registers_the_home_route(): void
    {
        $route = app('router')->getRoutes()->match(\Illuminate\Http\Request::create('/', 'GET'));

        $this->assertSame('/', $route->uri());
        $this->assertSame(['GET', 'HEAD'], $route->methods());
    }
}
