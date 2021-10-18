<?php

namespace App\Http\Middleware;

use App\Cookies\ClientIdCookie;
use App\Events\GetOnlineEvent;
use App\Models\UserEntity;
use Illuminate\Support\Str;

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
        $fakeUser = new UserEntity();
        $fakeUser->virtual_client = true;
        $fakeUser->id = rand(100,1000);
        $fakeUser->name = Str::random('10');

        $request->merge(['user' => $fakeUser]);
        $request->setUserResolver(function () use ($fakeUser) {
            return $fakeUser;
        });
        return $next($request);
    }
}
