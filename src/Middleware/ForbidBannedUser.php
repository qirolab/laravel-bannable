<?php

namespace Qirolab\Laravel\Bannable\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Qirolab\Laravel\Bannable\Exceptions\BannableTraitNotUsed;

class ForbidBannedUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Exception
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && ! method_exists($user, 'isBanned')) {
            throw new BannableTraitNotUsed(get_class($user));
        }

        if ($user && $user->isBanned()) {
            return redirect()->back()->withInput()->withErrors([
                'login' => 'This account is blocked.',
            ]);
        }

        return $next($request);
    }
}
