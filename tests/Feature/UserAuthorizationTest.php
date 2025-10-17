<?php

uses(Tests\TestCase::class, Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\patchJson;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;


test('a user can update their own profile', function () {
    // 1. Arrange: Cria um usuário
    $user = User::factory()->create();
    $newData = ['name' => 'New Name'];

    // 2. Act: Autentica como este usuário e tenta atualizar seu próprio perfil
    $response = actingAs($user)
        ->patchJson(route('users.update', ['user' => $user->id]), $newData);

    // 3. Assert: Verifica se a requisição foi bem-sucedida
    $response->assertSuccessful();

    // Verifica se o nome no banco de dados foi realmente atualizado
    assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'New Name',
    ]);
});

test('a user cannot update another user\'s profile', function () {
    // 1. Arrange: Cria dois usuários distintos
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    $newData = ['name' => 'Malicious Update'];

    // 2. Act: Autentica como UserA, mas tenta atualizar o perfil do UserB
    $response = actingAs($userA)
        ->patchJson(route('users.update', ['user' => $userB->id]), $newData);

    // 3. Assert: A requisição DEVE ser negada com um status 403 Forbidden
    $response->assertForbidden();

    // Garante que o nome do UserB NÃO foi alterado no banco de dados
    assertDatabaseMissing('users', [
        'id' => $userB->id,
        'name' => 'Malicious Update',
    ]);
});

test('an unauthenticated user cannot update any profile', function () {
    // 1. Arrange: Cria um usuário alvo
    $targetUser = User::factory()->create();
    $newData = ['name' => 'Guest Update'];

    // 2. Act: Tenta atualizar sem autenticação
    $response = patchJson(route('users.update', ['user' => $targetUser->id]), $newData);

    // 3. Assert: A requisição deve ser negada com um status 401 Unauthorized
    $response->assertUnauthorized();
});