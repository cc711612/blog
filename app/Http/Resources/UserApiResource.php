<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use App\Traits\ImageTrait;
use App\Models\Entities\UserEntity;

class UserApiResource extends JsonResource
{
    use ImageTrait;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'                => Arr::get($this, 'id'),
            'name'              => Arr::get($this, 'name'),
            'email'             => Arr::get($this, 'email'),
            'introduction'      => Arr::get($this, 'introduction'),
            'image'             => $this->handleUserImage(Arr::get($this, 'images.cover')),
            'created_at'        => is_null(Arr::get($this, 'created_at')) ? ''
                : Arr::get($this, 'created_at')->format('Y-m-d H:i:s'),
            'email_verified_at' => is_null(Arr::get($this,
                'email_verified_at')) ? null : Arr::get($this, 'email_verified_at')->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @return array
     * @Author: Roy
     * @DateTime: 2022/8/6 下午 12:17
     */
    public function show():array
    {
        return [
            'id'                => Arr::get($this, 'id'),
            'name'              => Arr::get($this, 'name'),
            'email'             => Arr::get($this, 'email'),
            'image'             => $this->handleUserImage(Arr::get($this, 'images.cover')),
            'created_at'        => Arr::get($this, 'created_at')->format('Y-m-d H:i:s'),
            'introduction'      => Arr::get($this, 'introduction'),
            'email_verified_at' => is_null(Arr::get($this,
                'email_verified_at')) ? null : Arr::get($this, 'email_verified_at')->format('Y-m-d H:i:s'),
        ];
    }
}
