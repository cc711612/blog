<?php

namespace App\Http\Middleware;

use App\Cookies\ClientIdCookie;
use App\Events\GetOnlineEvent;
use App\Models\UserEntity;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SocketOnline
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     *
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
//        event(new GetOnlineEvent(app(ClientIdCookie::class)->get()));
        $user = Auth::user();
        if (is_null(Auth::id())) {
            $user = new UserEntity();
            $user->virtual_client = true;
            $user->id = rand(100, 1000);
            $user->name = Str::random('10');
        }

        $request->merge(['user' => $user]);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });
        return $next($request);
    }
}
