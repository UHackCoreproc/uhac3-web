<?php

namespace UHacWeb\Http\Middleware;

use Closure;
use UHacWeb\Models\ApiKey;
use UHacWeb\Models\User;

class AuthenticateApiKey
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $apiKeyValue = $request->header(config('kitchen.api_key.header', 'X-Authorization'));

        $apiKey = ApiKey::where('key', $apiKeyValue)
            ->first();

        if (empty($apiKey)) {
            return $this->unauthorizedResponse();
        }

        $user = $apiKey->apikeyable;

        // Log the user in
        if ($user instanceof BackendUser) {
            SentinelBackend::login($user);
        } elseif ($user instanceof User) {
            Sentinel::login($user);
        }

        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }

    private function unauthorizedResponse()
    {
        return response([
            'error' => 401,
        ], 401);
    }

}
