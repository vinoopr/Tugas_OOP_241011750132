<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_redirects_guests_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/admin/projects')->assertRedirect('/login');
        $this->get('/admin/projects/create')->assertRedirect('/login');
        $this->get('/login')->assertOk();
    }

    public function test_logout_invalidates_authenticated_session(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_admin' => true])->save();
        $this->actingAs($admin);
        $this->withSession(['private_value' => 'secret'])->post('/logout')
            ->assertRedirect('/login')->assertSessionMissing('private_value');
        $this->assertGuest();
        $this->get('/admin')->assertRedirect('/login');
    }
}
