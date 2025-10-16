<?php

uses(Tests\TestCase::class, Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Models\User;
use Illuminate\Support\Facades\Http;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use Illuminate\Support\Facades\DB;


beforeEach(function () {
    $this->user = User::factory()->create();
});

it('can add a product to favorites', function () {
    Http::fake([
        'fakestoreapi.com/*' => Http::response(['id' => 1, 'title' => 'Test Product'], 200),
    ]);

    actingAs($this->user)
        ->postJson('/api/favorites', ['product_id' => 1])
        ->assertNoContent();

    $this->assertDatabaseHas('favorite_products', [
        'user_id' => $this->user->id,
        'product_id' => 1,
    ]);
});

it('cannot add a non-existent product to favorites', function () {
    Http::fake([
        'fakestoreapi.com/*' => Http::response(null, 404),
    ]);

    actingAs($this->user)
        ->postJson('/api/favorites', ['product_id' => 999])
        ->assertNotFound();
});

it('returns service unavailable when the external API fails on adding a favorite', function () {
    Http::fake([
        'fakestoreapi.com/*' => Http::response(null, 500),
    ]);

    actingAs($this->user)
        ->postJson('/api/favorites', ['product_id' => 999])
        ->assertStatus(503);
});

it('returns service unavailable when the external API fails on getting favorites', function () {
    Http::fake([
        'fakestoreapi.com/*' => Http::response(null, 500),
    ]);

    $this->user->favoriteProducts()->create(['product_id' => 1]);

    actingAs($this->user)
        ->getJson('/api/favorites')
        ->assertStatus(503);
});
it('can get favorite products', function () {
    Http::fake([
        'fakestoreapi.com/*' => Http::response(['id' => 1, 'title' => 'Test Product'], 200),
    ]);

    $this->user->favoriteProducts()->create(['product_id' => 1]);

    actingAs($this->user)
        ->getJson('/api/favorites')
        ->assertOk()
        ->assertJsonCount(1);
});

it('can remove a product from favorites', function () {
    $this->user->favoriteProducts()->create(['product_id' => 1]);

    actingAs($this->user)
        ->deleteJson('/api/favorites/1')
        ->assertNoContent();

    $this->assertDatabaseMissing('favorite_products', [
        'user_id' => $this->user->id,
        'product_id' => 1,
    ]);
});
