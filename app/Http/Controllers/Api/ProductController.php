<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FakeStoreApiService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(protected FakeStoreApiService $fakeStoreApi) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $products = $this->fakeStoreApi->getAllProducts();

        return response()->json($products);
    }
}
