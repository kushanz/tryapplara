<?php

namespace Tests\Feature\Users;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_list_users_with_pagination(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->count(18)->create();

        Sanctum::actingAs($admin, ['users:read', 'users:update']);

        $this->getJson('/api/users?per_page=10')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('meta.current_page', 1)
            ->assertJsonPath('meta.per_page', 10)
            ->assertJsonPath('meta.total', 19);
    }

    public function test_admin_can_search_users_by_name_or_email(): void
    {
        $admin = User::factory()->admin()->create();
        $matchedUser = User::factory()->create([
            'name' => 'John Searchable',
            'email' => 'john-search@example.com',
        ]);
        User::factory()->create([
            'name' => 'Another Person',
            'email' => 'another@example.com',
        ]);

        Sanctum::actingAs($admin, ['users:read', 'users:update']);

        $this->getJson('/api/users?search=john-search')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('meta.search', 'john-search')
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.email', $matchedUser->email);
    }

    public function test_manager_can_view_users_list_but_cannot_create_user(): void
    {
        $manager = User::factory()->manager()->create();
        User::factory()->count(2)->create();

        Sanctum::actingAs($manager, ['users:read']);

        $this->getJson('/api/users')
            ->assertOk();

        $this->postJson('/api/users', [
            'name' => 'Blocked User',
            'email' => 'blocked@example.com',
            'role' => UserRole::Customer->value,
            'password' => 'Password!123',
            'password_confirmation' => 'Password!123',
        ])->assertForbidden();
    }

    public function test_customer_cannot_access_users_module(): void
    {
        $customer = User::factory()->create();

        Sanctum::actingAs($customer, []);

        $this->getJson('/api/users')
            ->assertForbidden()
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'This action is unauthorized.')
            ->assertJsonMissing(['exception', 'file', 'line', 'trace']);
    }

    public function test_admin_can_create_user(): void
    {
        $admin = User::factory()->admin()->create();

        Sanctum::actingAs($admin, ['users:read', 'users:update']);

        $this->postJson('/api/users', [
            'name' => 'New Manager',
            'email' => 'manager@example.com',
            'role' => UserRole::Manager->value,
            'password' => 'Password!123',
            'password_confirmation' => 'Password!123',
        ])
            ->assertCreated()
            ->assertJsonPath('data.role', UserRole::Manager->value);

        $this->assertDatabaseHas('users', [
            'email' => 'manager@example.com',
            'role' => UserRole::Manager->value,
        ]);
    }

    public function test_admin_can_show_and_update_user(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($admin, ['users:read', 'users:update']);

        $this->getJson("/api/users/{$user->id}")
            ->assertOk()
            ->assertJsonPath('data.email', $user->email);

        $this->putJson("/api/users/{$user->id}", [
            'name' => 'Updated User',
            'role' => UserRole::Manager->value,
        ])
            ->assertOk()
            ->assertJsonPath('data.name', 'Updated User')
            ->assertJsonPath('data.role', UserRole::Manager->value);
    }

    public function test_admin_can_delete_user_but_not_self(): void
    {
        $admin = User::factory()->admin()->create();
        $targetUser = User::factory()->create();

        Sanctum::actingAs($admin, ['users:read', 'users:update']);

        $this->deleteJson("/api/users/{$targetUser->id}")
            ->assertOk();

        $this->assertDatabaseMissing('users', [
            'id' => $targetUser->id,
        ]);

        $this->deleteJson("/api/users/{$admin->id}")
            ->assertForbidden()
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'This action is unauthorized.')
            ->assertJsonMissing(['exception', 'file', 'line', 'trace']);
    }

    public function test_users_index_requires_authentication(): void
    {
        $this->getJson('/api/users')
            ->assertUnauthorized()
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Unauthenticated')
            ->assertJsonMissing(['exception', 'file', 'line', 'trace']);
    }

    public function test_missing_user_returns_clean_not_found_response(): void
    {
        $admin = User::factory()->admin()->create();

        Sanctum::actingAs($admin, ['users:read', 'users:update']);

        $this->getJson('/api/users/999999')
            ->assertNotFound()
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Resource not found')
            ->assertJsonMissing(['exception', 'file', 'line', 'trace']);
    }

    public function test_create_user_validates_required_fields(): void
    {
        $admin = User::factory()->admin()->create();

        Sanctum::actingAs($admin, ['users:read', 'users:update']);

        $this->postJson('/api/users', [])
            ->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonStructure([
                'errors' => [
                    'name',
                    'email',
                    'role',
                    'password',
                ],
            ]);
    }
}
