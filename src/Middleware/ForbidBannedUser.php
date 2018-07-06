<?php

namespace Hkp22\Laravel\Bannable\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Hkp22\Laravel\Bannable\Exceptions\BannableTraitNotUsed;

class ForbidBannedUser
{
    /**
     * The Guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * @param \Illuminate\Contracts\Auth\Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next)
    {
        $user = $this->auth->user();

        try {
            if ($user && $user->isBanned()) {
                return redirect()->back()->withInput()->withErrors([
                            'login' => 'This account is blocked.',
                        ]);
            }
        } catch (\BadMethodCallException $e) {
            throw new BannableTraitNotUsed($user);
        }

        return $next($request);
    }
}
