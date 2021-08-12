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
        Cache::put(sprintf(config('cache_key.api.member_token'),Arr::get(Auth::user(),'api_token')), Auth::user(), Carbon::now()->addMonth()->toDateTimeString());
    }

    /**
     * @return $this
     * @Author: Roy
     * @DateTime: 2021/8/12 下午 09:13
     */
    private function updateToken()
    {
        $user = Auth::user();
        if (Cache::has(sprintf(config('cache_key.api.member_token'),Arr::get(Auth::user(),'api_token')))) {
            # 清除cache
            Cache::forget(sprintf(config('cache_key.api.member_token'),Arr::get(Auth::user(),'api_token')));
        }
        $user->api_token = Str::random(20);
        $user->save();
        return $this;
    }
}

