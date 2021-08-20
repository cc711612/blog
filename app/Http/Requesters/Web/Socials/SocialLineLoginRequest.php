<?php

namespace App\Http\Requesters\Web\Socials;

use App\Concerns\Databases\Request;
use Illuminate\Support\Arr;
use App\Models\Supports\SocialType;

class SocialLineLoginRequest extends Request
{
    /**
     * @return null[]
     * @Author  : Roy
     * @DateTime: 2020/12/15 下午 03:02
     */
    protected function schema(): array
    {
        return [
            'name'                      => null,
            'email'                     => null,
            'image'                     => null,
            'social_type'               => SocialType::Line,
            'social_type_value'         => null,
            'token'                     => null,
            'socials.name'              => null,
            'socials.email'             => null,
            'socials.image'             => null,
            'socials.social_type'       => SocialType::Line,
            'socials.social_type_value' => null,
            'socials.token'             => null,
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
            'name'                      => Arr::get($row, 'name'),
            'email'                     => Arr::get($row, 'email'),
            'image'                     => Arr::get($row, 'avatar'),
            'social_type'               => SocialType::Facebook,
            'social_type_value'         => Arr::get($row, 'id'),
            'token'                     => Arr::get($row, 'token'),
            'socials.name'              => Arr::get($row, 'name'),
            'socials.email'             => Arr::get($row, 'email'),
            'socials.image'             => Arr::get($row, 'avatar'),
            'socials.social_type'       => SocialType::Facebook,
            'socials.social_type_value' => Arr::get($row, 'id'),
            'socials.token'             => Arr::get($row, 'token'),
        ];

    }

}
