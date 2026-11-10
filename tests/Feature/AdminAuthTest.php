<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_dashboard_or_any_crud_route(): void
    {
        foreach (['/admin', '/admin/projects', '/admin/projects/create', '/admin/projects/1/edit'] as $url) {
            $this->get($url)->assertRedirect('/login');
        }
        $this->post('/admin/projects', [])->assertRedirect('/login');
        $this->put('/admin/projects/1', [])->assertRedirect('/login');
        $this->delete('/admin/projects/1')->assertRedirect('/login');
        $this->get('/login')->assertOk();
        $this->get('/register')->assertNotFound();
        $this->post('/register', [])->assertNotFound();
        $this->get('/project')->assertOk();
    }

    public function test_only_admin_can_login_and_email_is_displayed_and_logout_protects_admin(): void
    {
        $admin = User::factory()->create(['email' => 'myadmin@example.com']);
        $admin->forceFill(['is_admin' => true])->save();
        $this->post('/login', ['email' => $admin->email, 'password' => 'wrong'])->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->post('/login', ['email' => $admin->email, 'password' => 'password'])->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);
        $this->get('/admin')->assertOk()->assertSee($admin->email)->assertSee('Administrator');
        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_regular_user_is_rejected_at_login_and_by_admin_middleware(): void
    {
        $user = User::factory()->create();
        $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->actingAs($user)->get('/admin')->assertForbidden();
        $this->post('/admin/projects', [])->assertForbidden();
    }

    public function test_admin_seeder_creates_login_account_and_updates_without_duplicates(): void
    {
        $this->seed(AdminSeeder::class);
        $admin = User::where('email', 'admin@example.com')->firstOrFail();
        $this->assertTrue($admin->is_admin);
        $this->assertTrue(Hash::check('GantiPassword123!', $admin->password));

        $admin->forceFill(['name' => 'Nama Lama', 'password' => 'password-lama', 'is_admin' => false])->save();
        $this->seed(AdminSeeder::class);
        $this->assertDatabaseCount('users', 1);
        $this->assertSame('Administrator', $admin->fresh()->name);
        $this->assertTrue($admin->fresh()->is_admin);
        $this->assertTrue(Hash::check('GantiPassword123!', $admin->fresh()->password));
        $this->post('/login', ['email' => $admin->email, 'password' => 'GantiPassword123!'])
            ->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);
    }
}
