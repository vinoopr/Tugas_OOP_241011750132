<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_is_accessible_without_login(): void
    {
        $this->get('/admin')->assertOk()->assertSee('Dashboard');
        $this->get('/admin/projects')->assertOk();
        $this->get('/admin/projects/create')->assertOk();
        $this->get('/login')->assertNotFound();
    }

    public function test_logout_returns_home_without_login(): void
    {
        $this->withSession(['private_value' => 'secret'])->post('/logout')
            ->assertRedirect('/')->assertSessionMissing('private_value');
        $this->assertGuest();
        $this->get('/admin')->assertOk();
    }
}
