<?php

namespace App\Http\Controllers\Api;

use App\Contracts\ProductServiceContract;
use App\Http\Controllers\Controller;
use App\Services\FakeStoreApiService;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(name="Products")
 */
class ProductController extends Controller
{
    public function __construct(protected ProductServiceContract $productService) {}

    /**
     * @OA\Get(
     * path="/api/products",
     * summary="Lists all products from the external API",
     * tags={"Products"},
     * @OA\Response(response=200, description="List of products")
     * )
     */
    public function index(): JsonResponse
    {
        // O serviço agora retorna um array de ProductDTO.
        // O Laravel cuidará da serialização para JSON.
        $products = $this->productService->getAllProducts();

        return response()->json($products);
    }
}
