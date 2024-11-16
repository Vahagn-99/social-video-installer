<?php

namespace App\Http\Middleware;

use App\Exceptions\OperationError;
use Closure;
use DefStudio\Telegraph\Facades\Telegraph;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RateLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sender_id =  $request->input("message.from.id");

        $rate_limiter_key = "send:new:downloading:request:$sender_id";

        RateLimiter::hit($rate_limiter_key);

        if (RateLimiter::tooManyAttempts($rate_limiter_key, 2)) {
            Telegraph::chat($sender_id)->message("Повторите попытку позднее")->send();

            return response()->noContent();
        }

        RateLimiter::hit($rate_limiter_key, 1);

        return $next($request);
    }
}
