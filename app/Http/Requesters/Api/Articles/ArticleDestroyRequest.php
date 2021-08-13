<?php

namespace App\Http\Requesters\Api\Articles;

use App\Concerns\Databases\Request;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class ArticleDestroyRequest extends Request
{
    /**
     * @return array
     * @Author: Roy
     * @DateTime: 2021/8/13 下午 09:39
     */
    protected function schema(): array
    {
        return [
            'id'                  => null,
            'updated_by'          => null,
            'deleted_by'          => null,
            'deleted_at'          => Carbon::now()->toDateTimeString(),
            'articles.updated_by' => null,
            'articles.deleted_by' => null,
            'articles.deleted_at' => Carbon::now()->toDateTimeString(),
        ];
    }

    /**
     * @param $row
     *
     * @return array
     * @Author: Roy
     * @DateTime: 2021/8/13 下午 09:39
     */
    protected function map($row): array
    {
        return [
            'id'                  => Arr::get($row, 'article'),
            'updated_by'          => Arr::get($row, 'user.id'),
            'deleted_by'          => Arr::get($row, 'user.id'),
            'articles.updated_by' => Arr::get($row, 'user.id'),
            'articles.deleted_by' => Arr::get($row, 'user.id'),
            'articles.deleted_at' => Arr::get($row, 'deleted_at'),
        ];
    }

}
