<?php

namespace Laravel\Fortify\Actions;

use Laravel\Fortify\LoginRateLimiter;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthLoginTrait;

/**
 * Class PrepareAuthenticatedSession
 *
 * @package Laravel\Fortify\Actions
 * @Author: Roy
 * @DateTime: 2021/8/12 下午 09:14
 */
class PrepareAuthenticatedSession
{
    use AuthLoginTrait;
    /**
     * The login rate limiter instance.
     *
     * @var \Laravel\Fortify\LoginRateLimiter
     */
    protected $limiter;

    /**
     * Create a new class instance.
     *
     * @param  \Laravel\Fortify\LoginRateLimiter  $limiter
     * @return void
     */
    public function __construct(LoginRateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        $request->session()->regenerate();

        $this->limiter->clear($request);
        # set cache
        $this->MemberTokenCache();
        return $next($request);
    }
}
