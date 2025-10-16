<?php

namespace App\Http\Controllers\Api;

use App\Contracts\ProductServiceContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFavoriteProductRequest;
use App\Models\FavoriteProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @OA\Tag(name="Favorites")
 */
class FavoriteProductController extends Controller
{
    public function __construct(private ProductServiceContract $fakeStoreApiService) { }

    /**
     * @OA\Get(
     *     path="/api/favorites",
     *     summary="Lists the favorite products of the authenticated user",
     *     tags={"Favorites"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="List of favorite products"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index(Request $request)
    {
        $favoriteProductIds = $request->user()->favoriteProducts()->pluck('product_id')->all();

        $products = $this->fakeStoreApiService->findProductsByIds($favoriteProductIds);

        return response()->json($products);
    }

    /**
     * @OA\Post(
     *     path="/api/favorites",
     *     summary="Adds a new favorite product",
     *     tags={"Favorites"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id"},
     *             @OA\Property(property="product_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=204, description="Favorite product added successfully"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Product not found")
     * )
     */
    public function store(StoreFavoriteProductRequest $request)
    {
        $productId = $request->validated()['product_id'];

        if (!$this->fakeStoreApiService->findProductById($productId)) {
            return response()->json(['message' => 'Product not found.'], Response::HTTP_NOT_FOUND);
        }

        $request->user()->favoriteProducts()->create(['product_id' => $productId]);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Delete(
     *     path="/api/favorites/{productId}",
     *     summary="Removes a favorite product",
     *     tags={"Favorites"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Favorite product removed successfully"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function destroy(Request $request, int $productId)
    {
        $request->user()->favoriteProducts()->where('product_id', $productId)->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}