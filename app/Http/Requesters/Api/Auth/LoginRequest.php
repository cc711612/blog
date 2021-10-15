<?php

namespace App\Http\Requesters\Api\Auth;

use App\Concerns\Databases\Request;
use Illuminate\Support\Arr;

class LoginRequest extends Request
{
    /**
     * @return null[]
     * @Author  : Roy
     * @DateTime: 2020/12/15 下午 03:02
     */
    protected function schema(): array
    {
        return [
            'email'    => null,
            'users.email' => null,
            'password'    => null,
            'users.password' => null,
        ];
    }

    /**
     * @param $row
     *
     * @return array
     * @Author  : Roy
     * @DateTime: 2020/12/15 下午 03:02
     */
    protected function map($row): array
    {
        return [
            'email'          => Arr::get($row, 'email'),
            'users.email'    => Arr::get($row, 'email'),
            'password'       => Arr::get($row, 'password'),
            'users.password' => Arr::get($row, 'password'),
        ];
    }

}
