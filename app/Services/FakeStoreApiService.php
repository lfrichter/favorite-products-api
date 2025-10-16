<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FakeStoreApiService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = Config::get('services.fakestore.base_url');
    }

    public function findProductById(int $productId): ?array
    {
        $response = Http::get("{$this->baseUrl}/products/{$productId}");

        Log::info('Fake Store API Response: ' . $response->status() . ' ' . $response->body());

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    public function getAllProducts(): array
    {
        $response = Http::get("{$this->baseUrl}/products");

        if ($response->successful()) {
            return $response->json();
        }

        Log::error("Falha ao buscar a lista de produtos na FakeStoreAPI.", [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        // Retorna um array vazio em caso de falha para evitar erros no controller
        return [];
    }
}