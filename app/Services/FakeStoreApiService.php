<?php

namespace App\Services;

use App\Contracts\ProductServiceContract;
use App\DTOs\ProductDTO;
use App\Exceptions\FakeStoreApiException;
use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class FakeStoreApiService implements ProductServiceContract
{
    public function __construct(
        private readonly HttpClient $http,
        private readonly string $baseUrl
    ) {}

    /**
     * @return ProductDTO|null
     */
    public function findProductById(int $productId): ?ProductDTO
    {
        $response = $this->http->get("{$this->baseUrl}/products/{$productId}");

        if ($response->status() === 404) {
            return null;
        }

        if ($response->failed()) {
            Log::error('Falha ao buscar o produto na FakeStoreAPI.', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new FakeStoreApiException('Failed to fetch product from Fake Store API.');
        }

        return $this->createDtoFromArray($response->json());
    }

    /**
     * @return ProductDTO[]
     */
    public function findProductsByIds(array $productIds): array
    {
        if (empty($productIds)) {
            return [];
        }

        // Utiliza um Pool para enviar todas as requisições em paralelo
        $responses = $this->http->pool(function (Pool $pool) use ($productIds) {
            foreach ($productIds as $productId) {
                $pool->get("{$this->baseUrl}/products/{$productId}");
            }
        });

        // Filtra respostas bem-sucedidas e converte para DTOs
        return collect($responses)
            ->filter(fn ($response) => $response->successful())
            ->map(fn ($response) => $this->createDtoFromArray($response->json()))
            ->values() // Re-indexa o array para chaves numéricas sequenciais
            ->all();
    }

    /**
     * @return ProductDTO[]
     */
    public function getAllProducts(): array
    {
        $response = $this->http->get("{$this->baseUrl}/products");

        if ($response->failed()) {
            Log::error('Falha ao buscar a lista de produtos na FakeStoreAPI.', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new FakeStoreApiException('Failed to fetch products from Fake Store API.');
        }

        return array_map(
            fn ($productData) => $this->createDtoFromArray($productData),
            $response->json()
        );
    }

    /**
     * Centraliza a criação do DTO a partir de um array.
     */
    private function createDtoFromArray(array $data): ProductDTO
    {
        return new ProductDTO(
            id: $data['id'],
            title: $data['title'],
            price: (float) $data['price'],
            description: $data['description'],
            category: $data['category'],
            image: $data['image'],
            rating: $data['rating'], // Assumindo que rating é um array ['rate' => x, 'count' => y]
        );
    }
}