<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FakeStoreApiService;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(name="Products")
 */
class ProductController extends Controller
{
    public function __construct(protected FakeStoreApiService $fakeStoreApi) {}

    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Lists all products from the external API",
     *     tags={"Products"},
     *     @OA\Response(response=200, description="List of products")
     * )
     */
    public function index(): JsonResponse
    {
        $products = $this->fakeStoreApi->getAllProducts();

        return response()->json($products);
    }
}
