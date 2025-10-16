<?php

uses(Tests\TestCase::class, Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Models\User;
use function Pest\Laravel\postJson;

it('can login with valid credentials', function () {
    $user = User::factory()->create();

    postJson('/api/auth/login', [
        'email' => $user->email,
        'password' => 'password',
    ])
        ->assertOk()
        ->assertJsonStructure(['token']);
});

it('cannot login with invalid credentials', function () {
    postJson('/api/auth/login', [
        'email' => 'wrong@email.com',
        'password' => 'wrong-password',
    ])->assertUnauthorized();
});

it('can logout', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api-token')->plainTextToken;

    postJson('/api/auth/logout', [], [
        'Authorization' => 'Bearer ' . $token,
    ])->assertNoContent();
});
