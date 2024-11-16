<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;

class BasicAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws BindingResolutionException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $authenticationHasPassed = false;

        if ($request->header('PHP_AUTH_USER') && $request->header('PHP_AUTH_PW')) {
            $username = $request->header('PHP_AUTH_USER');
            $password = $request->header('PHP_AUTH_PW');

            if ($username === config('auth.basic.username') && $password === config('auth.basic.password')) {
                $authenticationHasPassed = true;
            }
        }

        if ($authenticationHasPassed === false) {
            return response()->make('Invalid credentials.', 401, ['WWW-Authenticate' => 'Basic']);
        }

        return $next($request);
    }
}
