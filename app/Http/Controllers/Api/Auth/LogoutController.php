<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Cache;
use App\Traits\AuthLogoutTrait;


class LogoutController extends Controller
{
    use AuthLogoutTrait;
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


}
