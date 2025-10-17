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

    

            // A chamada ao serviço agora retorna diretamente um array de DTOs.

            // Nenhuma conversão é mais necessária aqui.

            return $this->productService->findProductsByIds($favoriteProductIds);

        }}
