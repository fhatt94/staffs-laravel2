<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MissingPageTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
       // $response = $this->get('/');
        $response = $this->get('/missing-page');

        // $response->assertStatus(200); --run php artisan make:test MissingPageTest will fail
        $response->assertStatus(404);
    }
}