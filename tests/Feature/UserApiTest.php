<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_validation_and_admin_flag_are_protected(): void
    {
        $this->postJson('/api/register', [])->assertUnprocessable()->assertJsonPath('success', false)->assertJsonValidationErrors(['name', 'email', 'password']);
        $payload = ['name' => 'API User', 'email' => 'api@example.com', 'password' => 'secret123', 'password_confirmation' => 'secret123', 'phone' => '08123456', 'address' => 'Jakarta', 'role' => 'admin', 'is_admin' => true];
        $response = $this->postJson('/api/register', $payload)->assertCreated()->assertJsonPath('token_type', 'Bearer')->assertJsonMissingPath('data.password')->assertJsonMissingPath('data.remember_token');
        $user = User::where('email', $payload['email'])->firstOrFail();
        $this->assertFalse($user->is_admin);
        $this->assertTrue(Hash::check($payload['password'], $user->password));
        $this->withToken($response->json('access_token'))->getJson('/api/users')->assertOk();
        $this->postJson('/api/register', $payload)->assertUnprocessable()->assertJsonValidationErrors('email');
    }

    public function test_login_and_logout_revoke_only_current_token(): void
    {
        $user = User::factory()->create();
        $otherToken = $user->createToken('other')->accessToken;
        $this->postJson('/api/login', ['email' => $user->email, 'password' => 'wrong'])->assertUnauthorized()->assertJsonPath('success', false);
        $this->postJson('/api/login', [])->assertUnprocessable()->assertJsonValidationErrors(['email', 'password']);
        $login = $this->postJson('/api/login', ['email' => $user->email, 'password' => 'password'])->assertOk();
        $token = $login->json('access_token');
        $this->withToken($token)->postJson('/api/logout')->assertOk();
        $this->assertDatabaseCount('personal_access_tokens', 1);
        $this->assertDatabaseHas('personal_access_tokens', ['id' => $otherToken->id]);
        app('auth')->forgetGuards();
        $this->withToken($token)->getJson('/api/users')->assertUnauthorized();
    }

    public function test_crud_validation_hashing_missing_users_and_token_cleanup(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;
        $this->withToken($token);
        $created = $this->postJson('/api/users', ['name' => 'New User', 'email' => 'new@example.com', 'password' => 'secret123', 'is_admin' => true])->assertCreated()->assertJsonMissingPath('data.password');
        $id = $created->json('data.id');
        $target = User::findOrFail($id);
        $this->assertFalse($target->is_admin);
        $this->assertTrue(Hash::check('secret123', $target->password));
        $target->createToken('target');
        $this->getJson('/api/users')->assertOk()->assertJsonCount(2, 'data');
        $this->getJson('/api/users/'.$id)->assertOk();
        $this->postJson('/api/users', ['name' => 'Duplicate', 'email' => 'new@example.com', 'password' => 'secret123'])->assertUnprocessable()->assertJsonValidationErrors('email');
        $this->patchJson('/api/users/'.$id, ['email' => $user->email])->assertUnprocessable()->assertJsonValidationErrors('email');
        $this->patchJson('/api/users/'.$id, ['email' => 'new@example.com', 'password' => null, 'phone' => '08123'])->assertOk()->assertJsonPath('data.phone', '08123');
        $this->assertSame($target->password, $target->fresh()->password);
        $this->putJson('/api/users/'.$id, ['name' => 'Updated', 'password' => 'changed123'])->assertOk();
        $this->assertTrue(Hash::check('changed123', $target->fresh()->password));
        $this->deleteJson('/api/users/'.$id)->assertOk();
        $this->assertDatabaseMissing('users', ['id' => $id]);
        $this->assertDatabaseMissing('personal_access_tokens', ['tokenable_id' => $id]);
        $this->getJson('/api/users/'.$id)->assertNotFound()->assertJsonPath('success', false);
        $this->patchJson('/api/users/'.$id, [])->assertNotFound();
        $this->deleteJson('/api/users/'.$id)->assertNotFound();
    }

    public function test_protected_endpoints_return_json_without_accept_header(): void
    {
        foreach (['get' => '/api/users', 'post' => '/api/logout', 'patch' => '/api/users/1', 'delete' => '/api/users/1'] as $method => $url) {
            $this->{$method}($url)->assertUnauthorized()->assertJsonPath('success', false);
        }
    }
}
