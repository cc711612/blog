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
use App\Http\Requesters\Web\Socials\SocialFacebookLoginRequest;
use App\Models\Services\SocialService;
use App\Models\Services\UserService;
use App\Http\Requesters\Api\Users\UserStoreRequest;
use App\Models\UserEntity;
use App\Http\Requesters\Web\Socials\SocialLineLoginRequest;
use App\Models\Entities\SocialEntity;
use App\Traits\AuthLoginTrait;

class SocialController extends BaseController
{
    use AuthLoginTrait;
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
     * @Author: Roy
     * @DateTime: 2021/8/20 上午 11:34
     */
    public function facebookReturn(Request $request)
    {
        $userInfo = Socialite::driver('facebook')->stateless()->user();
        $requester = (new SocialFacebookLoginRequest($this->object2array($userInfo)));
        # 檢查DB
        $Social = (new SocialService())
            ->setRequest($requester->toArray())
            ->findFaceBookEmail();
        # 不存在DB
        if (is_null($Social)) {
            # 先檢查User表Email重複性
            $User = (new UserService())
                ->checkUserEmail(Arr::get($requester, 'email'));
            if (is_null($User)) {
                # 新增
                $User = (new UserService())
                    ->setRequest([
                        UserEntity::Table => [
                            'name'     => Arr::get($requester, 'name'),
                            'email'    => Arr::get($requester, 'email'),
                            'password' => Hash::make(Str::random(10)),
                            'images'   => [
                                'cover' => Arr::get($requester, 'image'),
                            ],
                        ],
                    ])
                    ->create();
            }
            # 新增
            $Social = (new SocialService())
                ->setRequest($requester->toArray())
                ->create();

            $Social->users()->sync(['user_id' => $User->id]);
        } else {
            $User = $Social->users()->get()->first();
            # 更新
            $Social->update(Arr::get($requester, SocialEntity::Table));
        }
        Auth::login($User);
        # set cache
        $this->MemberTokenCache();
        return redirect('/');
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @Author: Roy
     * @DateTime: 2021/8/20 上午 11:35
     */
    public function facebookDelete(Request $request)
    {

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
     * @Author: Roy
     * @DateTime: 2021/8/20 上午 11:35
     */
    public function lineReturn(Request $request)
    {
        $userInfo = Socialite::driver('line')->user();
        $requester = (new SocialLineLoginRequest($this->object2array($userInfo)));
        # 檢查DB
        $Social = (new SocialService())
            ->setRequest($requester->toArray())
            ->findFaceBookEmail();
        # 不存在DB
        if (is_null($Social)) {
            # 先檢查User表Email重複性
            $User = (new UserService())
                ->checkUserEmail(Arr::get($requester, 'email'));
            if (is_null($User)) {
                # 新增
                $User = (new UserService())
                    ->setRequest([
                        UserEntity::Table => [
                            'name'     => Arr::get($requester, 'name'),
                            'email'    => Arr::get($requester, 'email'),
                            'password' => Hash::make(Str::random(10)),
                            'images'   => [
                                'cover' => Arr::get($requester, 'image'),
                            ],
                        ],
                    ])
                    ->create();
            }
            # 新增
            $Social = (new SocialService())
                ->setRequest($requester->toArray())
                ->create();

            $Social->users()->sync(['user_id' => $User->id]);
        } else {
            $User = $Social->users()->get()->first();
            # 更新
            $Social->update(Arr::get($requester, SocialEntity::Table));

        }
        Auth::login($User);
        # set cache
        $this->MemberTokenCache();
        return redirect('/');
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
