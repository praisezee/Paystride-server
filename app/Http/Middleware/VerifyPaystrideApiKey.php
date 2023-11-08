<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyPaystrideApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = config('app.paystride_api_key');

        $apiKeyIsValid = (
            ! empty($apiKey)
            && $request->header('x-api-key') == $apiKey
        );

        abort_if (! $apiKeyIsValid, 403, 'Access denied');

        return $next($request);
    }
}
