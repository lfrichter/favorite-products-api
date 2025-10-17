<?php

uses(Tests\TestCase::class, Illuminate\Foundation\Testing\RefreshDatabase::class);

use App\Contracts\ProductServiceContract;
use App\DTOs\ProductDTO;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->productServiceMock = $this->mock(ProductServiceContract::class);
    $this->app->instance(ProductServiceContract::class, $this->productServiceMock);
});

it('can add a product to favorites', function () {
    $this->productServiceMock->shouldReceive('findProductById')
        ->with(1)
        ->andReturn(new ProductDTO(
            id: 1,
            title: 'Test Product',
            price: 10.0,
            description: 'description',
            category: 'category',
            image: 'image',
            rating: ['rate' => 4.0, 'count' => 10]
        ));

    actingAs($this->user)
        ->postJson('/api/favorites', ['product_id' => 1])
        ->assertNoContent();

    $this->assertDatabaseHas('favorite_products', [
        'user_id' => $this->user->id,
        'product_id' => 1,
    ]);
});

it('cannot add a non-existent product to favorites', function () {
    $this->productServiceMock->shouldReceive('findProductById')
        ->with(999)
        ->andReturn(null);

    actingAs($this->user)
        ->postJson('/api/favorites', ['product_id' => 999])
        ->assertNotFound();
});

it('returns service unavailable when the external API fails on adding a favorite', function () {
    $this->productServiceMock->shouldReceive('findProductById')
        ->with(999)
        ->andThrow(new App\Exceptions\FakeStoreApiException());

    actingAs($this->user)
        ->postJson('/api/favorites', ['product_id' => 999])
        ->assertStatus(503);
});

it('returns service unavailable when the external API fails on getting favorites', function () {
    $this->productServiceMock->shouldReceive('findProductsByIds')
        ->andThrow(new App\Exceptions\FakeStoreApiException());

    $this->user->favoriteProducts()->create(['product_id' => 1]);

    actingAs($this->user)
        ->getJson('/api/favorites')
        ->assertStatus(503);
});
it('can get favorite products', function () {
    $this->productServiceMock->shouldReceive('findProductsByIds')
        ->andReturn([['id' => 1, 'title' => 'Test Product', 'price' => 10.0, 'description' => 'desc', 'category' => 'cat', 'image' => 'img', 'rating' => ['rate' => 4.0, 'count' => 10]]]);

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
