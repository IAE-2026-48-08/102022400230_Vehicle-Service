<?php

namespace App\Http\Middleware;

use App\Services\SsoJwtService;
use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class CaptureSsoJwt
{
    use ApiResponse;

    public function __construct(private SsoJwtService $ssoJwtService)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        try {
            $ssoUser = $this->ssoJwtService->captureUserFromRequest($request);
        } catch (Throwable $exception) {
            return $this->errorResponse($exception->getMessage(), null, 401);
        }

        $request->attributes->set('sso_user', $ssoUser);

        return $next($request);
    }
}
