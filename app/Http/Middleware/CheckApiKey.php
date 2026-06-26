<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiKey
{
    use ApiResponse;

    public function handle(Request $request, Closure $next): Response
    {
        $expectedApiKey = env('X_IAE_KEY_VALUE');
        $providedApiKey = $request->header('X-IAE-KEY');

        if ($providedApiKey !== $expectedApiKey) {
            return $this->errorResponse('API Key is missing or invalid', null, 401);
        }

        return $next($request);
    }
}
