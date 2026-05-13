<?php

namespace Tests\Feature;

use Tests\TestCase;

class RegistrationPageTest extends TestCase
{
    public function test_registration_page_loads_successfully(): void
    {
        $response = $this->get('/registration');

        $response->assertOk();
        $response->assertSee('Registration');
        $response->assertSee('Link not configured');
    }
}
