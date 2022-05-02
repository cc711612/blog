<?php

namespace App\Http\Controllers\Api\Users;

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
use App\Http\Requesters\Api\Users\UserShowRequest;
use App\Http\Validators\Api\Users\UserShowValidator;
use App\Models\Entities\UserEntity;
use App\Http\Requesters\Api\Users\UserStoreRequest;
use App\Http\Validators\Api\Users\UserStoreValidator;
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
        $Users = (new UserEntity())
            ->all();
        $Users = $Users->map(function ($userEntity) {
            return [
                'id'                => Arr::get($userEntity, 'id'),
                'name'              => Arr::get($userEntity, 'name'),
                'email'             => Arr::get($userEntity, 'email'),
                'introduction'      => Arr::get($userEntity, 'introduction'),
                'image'             => $this->handleUserImage(Arr::get($userEntity, 'images.cover')),
                'created_at'        => Arr::get($userEntity, 'created_at')->format('Y-m-d H:i:s'),
                'email_verified_at' => is_null(Arr::get($userEntity,
                    'email_verified_at')) ? null : Arr::get($userEntity, 'email_verified_at')->format('Y-m-d H:i:s'),
            ];
        });
        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => [],
            'data'    => $Users,
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2021/8/10 下午 11:52
     */
    public function show(Request $request)
    {
        $Requester = (new UserShowRequest($request));

        $Validate = (new UserShowValidator($Requester))->validate();
        if ($Validate->fails() === true) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => $Validate->errors(),
                'data'    => [],
            ]);
        }
        $User = (new UserEntity())->find(Arr::get($Requester, 'id'));
        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => [],
            'data'    => [
                'id'                => Arr::get($User, 'id'),
                'name'              => Arr::get($User, 'name'),
                'email'             => Arr::get($User, 'email'),
                'image'             => $this->handleUserImage(Arr::get($User, 'images.cover')),
                'created_at'        => Arr::get($User, 'created_at')->format('Y-m-d H:i:s'),
                'introduction'      => Arr::get($User, 'introduction'),
                'email_verified_at' => is_null(Arr::get($User,
                    'email_verified_at')) ? null : Arr::get($User, 'email_verified_at')->format('Y-m-d H:i:s'),
            ],
        ]);

    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2021/7/30 下午 02:01
     */
    public function store(Request $request)
    {
        $Requester = (new UserStoreRequest($request));

        $Validate = (new UserStoreValidator($Requester))->validate();
        if ($Validate->fails() === true) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => $Validate->errors(),
                'data'    => [],
            ]);
        }
        $Requester = $Requester->toArray();
        Arr::set($Requester, 'password', Hash::make(Arr::get($Requester, 'password')));
        #Create
        (new UserEntity())->create($Requester);
        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => [],
            'data'    => [],
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2021/7/30 下午 02:03
     */
    public function update(Request $request)
    {
        $Requester = (new UserUpdateRequest($request));
        $Validate = (new UserUpdateValidator($Requester))->validate();
        if ($Validate->fails() === true) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => $Validate->errors(),
                'data'    => [],
            ]);
        }
        $Requester = $Requester->toArray();
//        Arr::set($Requester, 'users.password', Hash::make(Arr::get($Requester, 'users.password')));
        #Create
        $Entity = (new UserEntity())->find(Arr::get($Requester, 'id'))
            ->update(Arr::get($Requester, 'users'));

        if ($Entity) {
            return response()->json([
                'status'  => true,
                'code'    => 200,
                'message' => [],
                'data'    => [],
            ]);
        }
        return response()->json([
            'status'  => false,
            'code'    => 400,
            'message' => ['error' => '系統異常'],
            'data'    => [],
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @Author: Roy
     * @DateTime: 2021/7/30 下午 02:13
     */
    public function destroy(Request $request)
    {
        $Requester = (new UserDestroyRequest($request));
        $Validate = (new UserDestroyValidator($Requester))->validate();
        if ($Validate->fails() === true) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => $Validate->errors(),
                'data'    => [],
            ]);
        }
        $Entity = (new UserEntity())->find(Arr::get($Requester, 'id'));
        if (is_null($Entity) === true) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => ['id' => 'not exist'],
                'data'    => [],
            ]);
        }
        #刪除
        if ($Entity->update(Arr::get($Requester, 'users'))) {
            return response()->json([
                'status'  => true,
                'code'    => 200,
                'message' => [],
                'data'    => [],
            ]);
        }
        return response()->json([
            'status'  => false,
            'code'    => 400,
            'message' => ['error' => '系統異常'],
            'data'    => [],
        ]);
    }

    /**
     * @return string
     * @Author: Roy
     * @DateTime: 2021/8/7 下午 02:32
     */
    public function getDefaultImage()
    {
        return sprintf('%s://%s%s%s', $_SERVER['REQUEST_SCHEME'], $_SERVER["HTTP_HOST"],
            config('filesystems.disks.images.url'), 'default.png');
    }
}
