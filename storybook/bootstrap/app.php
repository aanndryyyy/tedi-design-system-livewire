<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// Blast registers its /storybook_preview routes outside the `web` middleware
// group, so a story page has no session. A story that mounts a real Livewire
// component (FileUpload: Controlled Clearing, With Reactive Forms) then gets
// 419 on every request, because Livewire's update endpoint does check CSRF.
// Put Blast's routes in `web` once every provider has registered its routes.
$app->booted(function () use ($app) {
    $prefix = trim(str_replace(config('app.url'), '', config('blast.storybook_server_url')), '/');

    foreach ($app['router']->getRoutes() as $route) {
        if ($prefix !== '' && str_starts_with($route->uri(), $prefix)) {
            $route->middleware('web');
        }
    }
});

return $app;
