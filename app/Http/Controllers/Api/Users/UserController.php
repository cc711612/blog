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
use App\Http\Resources\UserApiResource;

class UserController extends BaseController
{

    use ImageTrait;

    /**
     * @var \App\Models\Entities\UserEntity
     */
    private $UserEntity;

    /**
     * @param  \App\Models\Entities\UserEntity  $UserEntity
     */
    public function __construct(UserEntity $UserEntity)
    {
        $this->UserEntity = $UserEntity;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2022/8/6 下午 12:18
     */
    public function index()
    {

        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => [],
            'data'    => UserApiResource::collection($this->UserEntity->all()),
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

        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => [],
            'data'    => (new UserApiResource($this->UserEntity->find(Arr::get($Requester, 'id'))))->show(),
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
        $this->UserEntity->create($Requester);
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
        $Entity = $this->UserEntity->find(Arr::get($Requester, 'id'))
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
     * @return \Illuminate\Http\JsonResponse
     * @Author: Roy
     * @DateTime: 2022/8/6 下午 12:18
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
        $Entity = $this->UserEntity->find(Arr::get($Requester, 'id'));

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
}
