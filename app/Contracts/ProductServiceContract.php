<?php

namespace App\Contracts;

interface ProductServiceContract
{
    public function findProductById(int $productId): ?array;

    public function findProductsByIds(array $productIds): array;

    public function getAllProducts(): array;
}
