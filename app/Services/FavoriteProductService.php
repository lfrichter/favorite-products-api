<?php

namespace App\Services;

use App\Contracts\ProductServiceContract;
use App\DTOs\ProductDTO;
use App\Models\User;

class FavoriteProductService
{
    public function __construct(private readonly ProductServiceContract $productService) { }

    public function getFavoriteProducts(User $user): array
    {
        $favoriteProductIds = $user->favoriteProducts()->pluck('product_id')->all();
        $productData = $this->productService->findProductsByIds($favoriteProductIds);

        return array_map(fn($data) => (
            new ProductDTO(
                id: $data['id'],
                title: $data['title'],
                price: (float) $data['price'],
                description: $data['description'],
                category: $data['category'],
                image: $data['image'],
                            rating: $data['rating'],
                        ))->toArray(), $productData);
                    }}
