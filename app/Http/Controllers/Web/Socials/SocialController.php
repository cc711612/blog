<?php

namespace App\Http\Controllers\Web\Socials;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Socialite;

class SocialController extends BaseController
{
    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @Author: Roy
     * @DateTime: 2021/8/20 上午 10:45
     */
    public function facebookLogin(Request $request)
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookReturn(Request $request)
    {
        dd($request);
    }

    public function lineLogin(Request $request)
    {
        dd($request);
    }

    public function lineReturn(Request $request)
    {
        dd($request);
    }


}
