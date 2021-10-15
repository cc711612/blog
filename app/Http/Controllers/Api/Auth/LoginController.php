<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Requesters\Api\Auth\LoginRequest;
use App\Http\Validators\Api\Auth\LoginValidator;
use Illuminate\Support\Facades\Auth;
use App\Traits\AuthLoginTrait;

/**
 * Class LoginController
 *
 * @package App\Http\Controllers\Api\Auth
 * @Author: Roy
 * @DateTime: 2021/8/12 下午 09:14
 */
class LoginController extends Controller
{
    use AuthLoginTrait;

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @Author: Roy
     * @DateTime: 2021/8/9 下午 04:12
     */
    public function login(Request $request)
    {
        $Requester = (new LoginRequest($request));

        $Validate = (new LoginValidator($Requester))->validate();
        if ($Validate->fails() === true) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => $Validate->errors(),
            ]);
        }
        $credentials = request(['email', 'password']);
        #認證失敗
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => ['password' => ['密碼有誤']],
            ]);
        }
        # set cache
        $this->MemberTokenCache();

        return response()->json([
            'status'   => true,
            'code'     => 200,
            'message'  => [],
            'data'     => [
                'id'           => Arr::get(Auth::user(), 'id'),
                'name'         => Arr::get(Auth::user(), 'name'),
                'email'        => Arr::get(Auth::user(), 'email'),
                'image'        => Arr::get(Auth::user(), 'images.cover', $this->getDefaultImage()),
                'member_token' => Arr::get(Auth::user(), 'api_token'),
            ],
            'redirect' => route('article.index'),
        ]);
    }


    /**
     * @return string
     * @Author: Roy
     * @DateTime: 2021/8/12 下午 09:14
     */
    private function getDefaultImage()
    {
        return sprintf('%s://%s%s%s', $_SERVER['REQUEST_SCHEME'], $_SERVER["HTTP_HOST"],
            config('filesystems.disks.images.url'), 'default.png');
    }


}
