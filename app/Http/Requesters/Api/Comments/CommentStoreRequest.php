<?php

namespace App\Http\Requesters\Api\Comments;

use App\Concerns\Databases\Request;
use Illuminate\Support\Arr;

class CommentStoreRequest extends Request
{
    /**
     * @return null[]
     * @Author  : Roy
     * @DateTime: 2020/12/15 下午 03:02
     */
    protected function schema(): array
    {
        return [
            'id'          => null,
            'user_id'             => null,
            'content'             => null,
            'created_by'          => null,
            'updated_by'          => null,
            'status'              => 1,
            'comments.article_id' => null,
            'comments.user_id'    => null,
            'comments.content'    => null,
            'comments.created_by' => null,
            'comments.updated_by' => null,
            'comments.status'     => 1,
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
            'id'          => Arr::get($row, 'article_id'),
            'user_id'             => Arr::get($row, 'user.id'),
            'content'             => Arr::get($row, 'content'),
            'created_by'          => Arr::get($row, 'user.id'),
            'updated_by'          => Arr::get($row, 'user.id'),
            'status'              => 1,
            'comments.article_id' => Arr::get($row, 'article_id'),
            'comments.user_id'    => Arr::get($row, 'user.id'),
            'comments.content'    => Arr::get($row, 'content'),
            'comments.created_by' => Arr::get($row, 'user.id'),
            'comments.updated_by' => Arr::get($row, 'user.id'),
            'comments.status'     => 1,
        ];
    }

}
