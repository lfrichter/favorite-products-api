<?php

namespace App\Contracts;

use App\DTOs\ProductDTO;

interface ProductServiceContract
{
    /**
     * @return ProductDTO|null
     */
    public function findProductById(int $productId): ?ProductDTO;

    /**
     * @param int[] $productIds
     * @return ProductDTO[]
     */
    public function findProductsByIds(array $productIds): array;

    /**
     * @return ProductDTO[]
     */
    public function getAllProducts(): array;
}
