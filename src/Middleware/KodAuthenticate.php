<?php

namespace Senasgr\KodExplorer\Middleware;

use Closure;
use Illuminate\Http\Request;
use Senasgr\KodExplorer\Auth\KodAuthManager;

class KodAuthenticate
{
    protected $auth;

    public function __construct(KodAuthManager $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $this->auth->handle($request, $next);
    }
}
