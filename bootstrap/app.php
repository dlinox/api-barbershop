<?php

use App\Common\Exceptions\ApiException;
use App\Common\Exceptions\PermissionDeniedException;
use App\Common\Http\Responses\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            \App\Common\Http\Middleware\ForceJsonResponse::class,
        ]);

        $middleware->alias([
            'permission'   => \App\Common\Http\Middleware\CheckPermission::class,
            'super_admin'  => \App\Common\Http\Middleware\CheckSuperAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Errores de autenticación
        $exceptions->render(function (AuthenticationException $e) {
            return ApiResponse::unauthorized($e->getMessage());
        });

        // Errores de validación
        $exceptions->render(function (ValidationException $e) {
            return ApiResponse::validationError(
                $e->errors(),
                $e->getMessage()
            );
        });

        // Errores de API personalizados
        $exceptions->render(function (ApiException $e) {
            $code = $e->getCode() ?: 400;
            return ApiResponse::error($e->getMessage(), null, $code);
        });

        // Errores de permisos
        $exceptions->render(function (PermissionDeniedException $e) {
            return ApiResponse::error($e->getMessage(), null, 403);
        });

        // Cualquier otra excepción no manejada
        $exceptions->render(function (Throwable $e) {
            if (app()->hasDebugModeEnabled()) {
                return ApiResponse::serverError($e->getMessage(), [
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => collect($e->getTrace())->take(5)->toArray(),
                ]);
            }

            return ApiResponse::serverError('Internal server error');
        });
    })
    ->create();
