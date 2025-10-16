<?php

uses(Tests\TestCase::class, Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Models\User;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;
use function Pest\Laravel\actingAs;

it('can create a user', function () {
    $data = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
    ];

    postJson('/api/users', $data)
        ->assertCreated()
        ->assertJsonFragment(['email' => 'test@example.com']);
});

it('can get a user', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->getJson("/api/users/{$user->id}")
        ->assertOk()
        ->assertJsonFragment(['email' => $user->email]);
});

it('can update a user', function () {
    $user = User::factory()->create();

    $data = [
        'name' => 'Updated Name',
    ];

    actingAs($user)
        ->putJson("/api/users/{$user->id}", $data)
        ->assertOk()
        ->assertJsonFragment(['name' => 'Updated Name']);
});

it('can delete a user', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->deleteJson("/api/users/{$user->id}")
        ->assertNoContent();

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});
