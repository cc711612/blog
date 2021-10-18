<?php

namespace App\Http\Requesters\Api\Comments;

use App\Concerns\Databases\Request;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class CommentDestroyRequest extends Request
{
    /**
     * @return null[]
     * @Author  : Roy
     * @DateTime: 2020/12/15 下午 03:02
     */
    protected function schema(): array
    {
        return [
            'id'                  => null,
            'deleted_by'          => null,
            'updated_by'          => null,
            'comments.deleted_by' => null,
            'comments.updated_by' => 1,
            'comments.deleted_at' => Carbon::now()->toDateTimeString(),
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
            'id'                  => Arr::get($row, 'comment'),
            'deleted_by'          => Arr::get($row, 'user.id'),
            'updated_by'          => Arr::get($row, 'user.id'),
            'comments.updated_by' => Arr::get($row, 'user.id'),
            'comments.deleted_by' => Arr::get($row, 'user.id'),
            'comments.deleted_at' => Arr::get($row, 'deleted_at'),
        ];
    }

}
