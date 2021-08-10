<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Cache;


class LogoutController extends Controller
{
    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @Author: Roy
     * @DateTime: 2021/8/9 下午 04:12
     */
    public function logout(Request $request)
    {
        # clean cache
        $this->cleanToken(Arr::get($request,'user.api_token'));

        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => [],
            'data'    => [],
        ]);
    }

    /**
     * @param  string  $token
     *
     * @Author: Roy
     * @DateTime: 2021/8/10 下午 11:59
     */
    private function cleanToken(string $token)
    {
        if (Cache::has(sprintf(config('cache_key.api.member_token'), $token))) {
            # 清除cache
            Cache::forget(sprintf(config('cache_key.api.member_token'), $token));
        }
        return $this;
    }
}
