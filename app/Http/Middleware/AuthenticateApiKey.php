<?php

namespace UHacWeb\Http\Middleware;

use Auth;
use Closure;
use EllipseSynergie\ApiResponse\Contracts\Response;
use UHacWeb\Models\ApiKey;
use UHacWeb\Models\User;

class AuthenticateApiKey
{
    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

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
        $apiKeyValue = $request->header('X-Authorization');

        $apiKey = ApiKey::where('key', $apiKeyValue)
            ->first();

        if (empty($apiKey)) {
            return $this->response->errorUnauthorized('Invalid API key.');
        }

        $user = $apiKey->apikeyable;

        // Log the user in
        if ($user instanceof User) {
            Auth::login($user);
        }

        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }

}
