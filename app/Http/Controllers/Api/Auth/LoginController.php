<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Http\Requesters\Api\Auth\LoginRequest;
use App\Http\Validators\Api\Auth\LoginValidator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class LoginController extends Controller
{
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
        dump(bcrypt(Arr::get($Requester,'password')));
        dd((new User())->find(21)->password);
        $User = (new User())
            ->where('email',Arr::get($Requester,'email'))
            ->where('password',Hash::make(Arr::get($Requester,'password')))
            ->get();

        if($User->isEmpty()){
            return response()->json([
                'status'  => false,
                'code'    => 400,
                'message' => ['password'=>'密碼有誤'],
            ]);
        }
        $User->api_token = Str::random(10);
        $User->save();
        dd($User);
    }
}
