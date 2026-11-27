<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();
        $middleware->redirectUsersTo('/admin');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(fn ($request, $e) => $request->is('api/*') || $request->expectsJson());
        $exceptions->render(function (Throwable $e, Request $request) {
            if (! $request->is('api/*')) {
                return null;
            }
            if ($e instanceof ValidationException) {
                return response()->json(['success' => false, 'message' => 'Validasi gagal.', 'errors' => $e->errors()], 422);
            }
            $status = $e instanceof AuthenticationException ? 401
                : ($e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500);
            $message = match ($status) {
                403 => 'Akses ini hanya untuk admin.',
                419 => 'Sesi kedaluwarsa. Muat ulang halaman dan login kembali.',
                401 => 'Token autentikasi tidak valid atau belum diberikan.',
                404 => 'Data atau endpoint tidak ditemukan.',
                405 => 'Metode HTTP tidak diizinkan.',
                default => 'Permintaan gagal diproses.',
            };

            return response()->json(['success' => false, 'message' => $message, 'data' => null], $status);
        });
    })->create();
