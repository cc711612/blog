<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use App\Traits\ImageTrait;

class CommentApiResource extends JsonResource
{
    use ImageTrait;

    /**
     * @param $request
     *
     * @return array
     * @Author: Roy
     * @DateTime: 2022/8/6 下午 01:03
     */
    public function toArray($request)
    {
        return [
            'id'         => Arr::get($this, 'id'),
            'user'       => [
                'id'    => Arr::get($this, 'users.id'),
                'name'  => Arr::get($this, 'users.name'),
                'image' => Arr::get($this, 'users.images.cover', $this->getDefaultImage()),
            ],
            'content'    => Arr::get($this, 'content'),
            'updated_at' => Arr::get($this, 'updated_at')->format('Y-m-d H:i:s'),
            'logs'       => Arr::get($this, 'logs', []),
        ];
    }
}
