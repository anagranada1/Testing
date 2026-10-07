<?php

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);

        // Transbank devuelve al usuario con un POST cross-site (token_ws),
        // sin token CSRF y sin cookie de sesión (SameSite=lax).
        $middleware->validateCsrfTokens(except: [
            'finalUrl', 'url', 'forms/*/return'
        ]);

        $middleware->trustProxies(at: '*'); //QUITAR
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
