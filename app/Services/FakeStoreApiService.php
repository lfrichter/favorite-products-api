<?php

namespace App\Services;

use App\Contracts\ProductServiceContract;

use App\Exceptions\FakeStoreApiException;

use Illuminate\Http\Client\Factory as HttpClient;

use Illuminate\Support\Facades\Log;



class FakeStoreApiService implements ProductServiceContract

{

    public function __construct(

        private readonly HttpClient $http,

        private readonly string $baseUrl

    ) {}



    public function findProductById(int $productId): ?array

    {

        $response = $this->http->get("{$this->baseUrl}/products/{$productId}");



        if ($response->status() === 404) {

            return null;

        }



        if ($response->failed()) {

            Log::error("Falha ao buscar o produto na FakeStoreAPI.", [

                'status' => $response->status(),

                'body' => $response->body(),

            ]);



            throw new FakeStoreApiException('Failed to fetch product from Fake Store API.');

        }



        return $response->json();

    }



    public function findProductsByIds(array $productIds): array

    {

        $products = [];



        foreach ($productIds as $productId) {

            $product = $this->findProductById($productId);

            if ($product) {

                $products[] = $product;

            }

        }



        return $products;

    }



    public function getAllProducts(): array

    {

        $response = $this->http->get("{$this->baseUrl}/products");



        if ($response->failed()) {

            Log::error("Falha ao buscar a lista de produtos na FakeStoreAPI.", [

                'status' => $response->status(),

                'body' => $response->body(),

            ]);



            throw new FakeStoreApiException('Failed to fetch products from Fake Store API.');

        }



        return $response->json();

    }

}
