<?php

namespace Hkp22\Laravel\Bannable\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Hkp22\Laravel\Bannable\Exceptions\BannableTraitNotUsed;

class ForbidBannedUser
{
    /**
     * The Auth implementation.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * @param \Illuminate\Contracts\Auth\Factory $auth
     */
    public function __construct(Auth $auth)
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
