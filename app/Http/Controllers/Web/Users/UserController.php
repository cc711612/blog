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

class UserController extends BaseController
{
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
            return (object)[
                'id'                => Arr::get($userEntity, 'id'),
                'name'              => Arr::get($userEntity, 'name'),
                'email'             => Arr::get($userEntity, 'email'),
                'image'             => Arr::get($userEntity, 'images.cover', $this->getDefaultImage()),
                'created_at'        => Arr::get($userEntity, 'created_at')->format('Y-m-d H:i:s'),
                'email_verified_at' => is_null(Arr::get($userEntity,
                    'email_verified_at')) ? null : Arr::get($userEntity, 'email_verified_at')->format('Y-m-d H:i:s'),
            ];
        });

        return view('welcome',compact('Users'));
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
        $Requester = (new ArticleStoreRequest($request));

        $Validate = (new ArticleStoreValidator($Requester))->validate();
        if ($Validate->fails() === true) {
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => $Validate->errors(),
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
            ]);
        }
        $Requester = $Requester->toArray();
        Arr::set($Requester, 'users.password', Hash::make(Arr::get($Requester, 'users.password')));
        #Create
        $Entity = (new UserEntity())->find(Arr::get($Requester, 'id'))
            ->update($Requester);
        if ($Entity) {
            return response()->json([
                'status'  => true,
                'code'    => 200,
                'message' => [],
            ]);
        }
        return response()->json([
            'status'  => false,
            'code'    => 400,
            'message' => ['error' => '系統異常'],
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
            ]);
        }
        $Entity = (new UserEntity())->find(Arr::get($Requester, 'id'));
        if(is_null($Entity) === true){
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => ['id' => 'not exist'],
            ]);
        }
        #刪除
        if ($Entity->delete()) {
            return response()->json([
                'status'  => true,
                'code'    => 200,
                'message' => [],
            ]);
        }
        return response()->json([
            'status'  => false,
            'code'    => 400,
            'message' => ['error' => '系統異常'],
        ]);
    }
    /**
     * @return string
     * @Author: Roy
     * @DateTime: 2021/8/7 下午 02:32
     */
    public function getDefaultImage()
    {
        return sprintf('%s://%s%s%s',$_SERVER['REQUEST_SCHEME'] ,$_SERVER["HTTP_HOST"], config('filesystems.disks.images.url'), 'default.png');
    }
}
