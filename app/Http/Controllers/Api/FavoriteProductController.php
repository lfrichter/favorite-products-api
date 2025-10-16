<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FavoriteProduct;
use App\Services\FakeStoreApiService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FavoriteProductController extends Controller
{
    public function __construct(private FakeStoreApiService $fakeStoreApiService) { }

    public function index(Request $request)
    {
        $favoriteProducts = $request->user()->favoriteProducts;

        $products = $favoriteProducts->map(function ($favoriteProduct) {
            return $this->fakeStoreApiService->findProductById($favoriteProduct->product_id);
        });

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $productId = $request->validate(['product_id' => 'required|integer'])['product_id'];

        if (!$this->fakeStoreApiService->findProductById($productId)) {
            return response()->json(['message' => 'Product not found.'], Response::HTTP_NOT_FOUND);
        }

        $request->user()->favoriteProducts()->create(['product_id' => $productId]);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function destroy(Request $request, int $productId)
    {
        $request->user()->favoriteProducts()->where('product_id', $productId)->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}