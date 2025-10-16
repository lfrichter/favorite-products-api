<?php

use App\Exceptions\FakeStoreApiException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (FakeStoreApiException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Service Unavailable. Could not connect to external service.'
                ], Response::HTTP_SERVICE_UNAVAILABLE);
            }
        });
    })->create();
