<?php

use App\Http\Middleware\AddInPermission;
use App\Http\Middleware\PermissionCheck;
use App\Http\Middleware\RemoveFromPermission;
use App\Http\Middleware\ShowLoadingSpinner;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            ShowLoadingSpinner::class,
        ]);

        $middleware->alias([
            'permission.add' => AddInPermission::class,
            'permission.remove' => RemoveFromPermission::class,
            'permission.check' => PermissionCheck::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
