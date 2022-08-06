<?php

namespace App\Http\Controllers\Web\Users;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Validators\Api\Users\ArticleStoreValidator;
use App\Http\Requesters\Api\Users\ArticleStoreRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requesters\Api\Users\UserUpdateRequest;
use App\Http\Validators\Api\Users\UserUpdateValidator;
use App\Http\Requesters\Api\Users\UserDestroyRequest;
use App\Http\Validators\Api\Users\UserDestroyValidator;
use App\Models\Entities\UserEntity;
use App\Traits\ImageTrait;

class UserController extends BaseController
{
    use ImageTrait;
    /**
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2021/7/30 下午 01:04
     */
    public function index()
    {
        # 測試用
        $Users = (new UserEntity())
            ->all();
        $Users = $Users->map(function ($userEntity) {
            return (object)[
                'id'                => Arr::get($userEntity, 'id'),
                'name'              => Arr::get($userEntity, 'name'),
                'email'             => Arr::get($userEntity, 'email'),
                'image'             => $this->handleUserImage(Arr::get($userEntity, 'images.cover')),
                'created_at'        => Arr::get($userEntity, 'created_at')->format('Y-m-d H:i:s'),
                'email_verified_at' => is_null(Arr::get($userEntity,
                    'email_verified_at')) ? null : Arr::get($userEntity, 'email_verified_at')->format('Y-m-d H:i:s'),
            ];
        });

        return view('welcome',compact('Users'));
    }
}
