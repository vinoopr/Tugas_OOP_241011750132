<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_page_requires_an_admin_session(): void
    {
        $this->get('/admin/users')->assertRedirect('/login');
        $this->actingAs(User::factory()->create())->get('/admin/users')->assertForbidden();
        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->get('/admin/users')->assertOk()->assertSee('users-table')->assertSee('user-modal');
    }

    public function test_browser_session_can_manage_users_through_sanctum(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->post('/login', ['email' => $admin->email, 'password' => 'password'])->assertRedirect('/admin');
        app('auth')->forgetGuards();
        $this->withHeader('Referer', 'http://localhost/admin/users');
        $this->getJson('/api/users')->assertOk()->assertJsonMissingPath('data.0.password');
        $created = $this->postJson('/api/users', [
            'name' => 'Browser User', 'email' => 'browser@example.com', 'password' => 'secret123',
            'phone' => '08123', 'address' => 'Jakarta', 'role' => 'staff', 'is_admin' => true,
        ])->assertCreated()->assertJsonPath('data.is_admin', false);
        $id = $created->json('data.id');
        $hash = User::findOrFail($id)->password;
        $this->getJson('/api/users/'.$id)->assertOk()->assertJsonPath('data.phone', '08123');
        $this->putJson('/api/users/'.$id, ['name' => 'Updated', 'password' => ''])->assertOk();
        $this->assertSame($hash, User::findOrFail($id)->password);
        $this->assertTrue(Hash::check('secret123', $hash));
        $this->deleteJson('/api/users/'.$admin->id)->assertUnprocessable();
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
        $this->deleteJson('/api/users/'.$id)->assertOk();
        $this->assertDatabaseMissing('users', ['id' => $id]);
    }

    public function test_regular_token_cannot_manage_users(): void
    {
        $user = User::factory()->create();
        $this->withToken($user->createToken('test')->plainTextToken);
        $this->getJson('/api/users')->assertForbidden();
        $this->getJson('/api/users/'.$user->id)->assertForbidden();
        $this->postJson('/api/users', [])->assertForbidden();
        $this->putJson('/api/users/'.$user->id, ['name' => 'Changed'])->assertForbidden();
        $this->deleteJson('/api/users/'.$user->id)->assertForbidden();
    }

    public function test_cookie_authenticated_mutations_require_a_csrf_token(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);
        $this->app->instance('env', 'local');
        $this->withHeader('Referer', 'http://localhost/admin/users');
        $payload = ['name' => 'CSRF User', 'email' => 'csrf@example.com', 'password' => 'secret123'];

        $this->postJson('/api/users', $payload)->assertStatus(419);
        $this->withSession(['_token' => 'user-crud-csrf-token'])
            ->withHeader('X-CSRF-TOKEN', 'user-crud-csrf-token')
            ->postJson('/api/users', $payload)->assertCreated();
    }
}
