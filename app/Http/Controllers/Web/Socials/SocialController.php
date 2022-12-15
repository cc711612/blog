<?php

namespace App\Http\Controllers\Web\Socials;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Socialite;
use App\Http\Requesters\Web\Socials\SocialFacebookLoginRequest;
use App\Models\Services\SocialService;
use App\Models\Services\UserService;
use App\Http\Requesters\Web\Socials\SocialLineLoginRequest;
use App\Traits\AuthLoginTrait;
use App\Macros\Auth\Contracts\LoginAdapter;
use App\Macros\Auth\Adapters\FacebookLoginAdapter;
use App\Macros\Auth\LoginMacro;
use Illuminate\Container\Container;
use App\Macros\Auth\Adapters\LineLoginAdapter;
use Illuminate\Support\Facades\Log;

class SocialController extends BaseController
{
    use AuthLoginTrait;

    /**
     * @var \App\Models\Services\SocialService
     */
    private $SocialService;
    /**
     * @var \App\Models\Services\UserService
     */
    private $UserService;

    /**
     * @param  \App\Models\Services\SocialService  $SocialService
     * @param  \App\Models\Services\UserService  $UserService
     */
    public function __construct
    (
        SocialService $SocialService,
        UserService $UserService
    ) {
        $this->SocialService = $SocialService;
        $this->UserService = $UserService;
    }

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

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @Author: Roy
     * @DateTime: 2022/12/14 上午 11:37
     */
    public function facebookReturn(Request $request)
    {
        $status = false;
        try {
            $userInfo = Socialite::driver('facebook')->stateless()->user();
            $requester = (new SocialFacebookLoginRequest($this->object2array($userInfo)));
            $container = Container::getInstance();
            $container->bind(LoginAdapter::class, FacebookLoginAdapter::class);
            $status = $container->make(LoginMacro::class)
                ->setParams($requester->toArray())
                ->login();
        } catch (\Exception $exception) {
            Log::channel('errorlog')->info(sprintf("LineLogin errors : %s ",
                json_encode($exception, JSON_UNESCAPED_UNICODE)));
        }

        if ($status) {
            # set cache
            $this->MemberTokenCache();
            return redirect(route('website.index'));
        }

        return redirect(route('login', ['message' => "登入發生錯誤"]));
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2022/12/14 下午 02:19
     */
    public function facebookDelete(Request $request)
    {
        return response()->json([
            'status' => true,
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @Author: Roy
     * @DateTime: 2021/8/20 上午 11:35
     */
    public function lineLogin(Request $request)
    {
        return Socialite::driver('line')->redirect();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @Author: Roy
     * @DateTime: 2022/12/14 上午 11:37
     */
    public function lineReturn(Request $request)
    {
        $status = false;
        try {
            $userInfo = Socialite::driver('line')->user();
            $requester = (new SocialLineLoginRequest($this->object2array($userInfo)));
            $container = Container::getInstance();
            $container->bind(LoginAdapter::class, LineLoginAdapter::class);
            $status = $container->make(LoginMacro::class)
                ->setParams($requester->toArray())
                ->login();
        } catch (\Exception $exception) {
            Log::channel('errorlog')->info(sprintf("LineLogin errors : %s ",
                json_encode($exception, JSON_UNESCAPED_UNICODE)));
        }

        if ($status) {
            # set cache
            $this->MemberTokenCache();
            return redirect(route('website.index'));
        }
        return redirect(route('login', ['message' => "登入發生錯誤"]));
    }

    /**
     * @param $object
     *
     * @return mixed
     * @Author: Roy
     * @DateTime: 2021/8/20 下午 03:28
     */
    function object2array($object)
    {
        return json_decode(json_encode(($object)), true);
    }
}
