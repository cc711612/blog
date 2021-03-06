<?php
/**
 * @Author: Roy
 * @DateTime: 2021/8/12 下午 09:04
 */

namespace App\Traits;


use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Cache;
use Illuminate\Support\Facades\Log;

trait AuthLoginTrait
{
    /**
     * @Author: Roy
     * @DateTime: 2021/8/12 下午 09:13
     */
    private function MemberTokenCache()
    {
        # 更新token
        $this->updateToken();
    }

    /**
     * @return $this
     * @Author: Roy
     * @DateTime: 2021/8/12 下午 09:13
     */
    private function updateToken()
    {
        try {
            # 檢查token
            if ($this->checkToken()) {
                # 清除cache
                $this->cleanToken();
            }
            $user = Auth::user();
            $user->api_token = Str::random(20);
            $cache = Cache::add(sprintf(config('cache_key.api.member_token'), $user->api_token), Auth::user(),
                config('app.login_timeout'));
            # Log
            if ($cache === true) {
                Log::channel('token')->info(sprintf("Login info : %s ", json_encode([
                    'user_id'   => $user->id,
                    'cache_key' => sprintf(config('cache_key.api.member_token'), $user->api_token),
                    'token'     => $user->api_token,
                    'end_time'  => Carbon::now()->addSeconds(config('app.login_timeout'))->toDateTimeString(),
                ])));
            }
            $user->save();
        } catch (\Throwable $exception) {
            Log::channel('errorlog')->info(sprintf("Login errors : %s ", json_encode($exception, JSON_UNESCAPED_UNICODE)));
        }
        return $this;
    }

    /**
     * @param  string|null  $token
     *
     * @return bool
     * @Author: Roy
     * @DateTime: 2021/8/13 上午 10:44
     */
    private function checkToken(string $token = null)
    {
        if (is_null($token) === true) {
            $token = Arr::get(Auth::user(), 'api_token');
        }
        return Cache::has($this->getCacheKey($token));
    }

    /**
     * @return bool
     * @Author: Roy
     * @DateTime: 2021/8/13 上午 10:36
     */
    private function cleanToken()
    {
        Log::channel('token')->info(sprintf("Token Clean info : %s ", $this->getCacheKey()));
        return Cache::forget($this->getCacheKey());
    }

    /**
     * @param  string|null  $token
     *
     * @Author: Roy
     * @DateTime: 2021/8/13 上午 10:47
     */
    private function getCacheKey(string $token = null)
    {
        if (is_null($token) === true) {
            $token = Arr::get(Auth::user(), 'api_token');
        }
        return sprintf(config('cache_key.api.member_token'), $token);
    }
}

