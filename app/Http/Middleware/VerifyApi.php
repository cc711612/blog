<?php

namespace App\Http\Middleware;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Cache;

class VerifyApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, \Closure $next, $guard = null)
    {
        $member_token = $request->member_token;

        if($member_token == null){
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => [ 'member_token' => ['請帶入 member_token']],
                'data' => []
            ]);
        }
        $cache_key = sprintf("member_token.%s",$member_token);

        if(Cache::has($cache_key) == false){
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => [ 'member_token' => ['請重新登入']],
                'data' => []
            ]);
        }
        # 取得快取資料
        $LoginCache = Cache::get($cache_key);

        # 若有新增請記得至 ResponseApiServiceProvider 排除
        $request->merge([
            'user' => $LoginCache
        ]);

        return $next($request);
    }
}
