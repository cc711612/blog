<?php

namespace App\Http\Requesters\Api\Articles;

use App\Concerns\Databases\Request;
use Illuminate\Support\Arr;

class ArticleStoreRequest extends Request
{
    /**
     * @return null[]
     * @Author  : Roy
     * @DateTime: 2020/12/15 下午 03:02
     */
    protected function schema(): array
    {
        return [
            'title' => null,
            'user_id' => null,
            'content' => null,
            'created_by' => null,
            'updated_by' => null,
            'status' => 1,
            'seo.keyword' => null,
            'seo.description' => null,
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
            'title' => Arr::get($row, 'title'),
            'content' => Arr::get($row, 'content'),
            'user_id' => Arr::get($row, 'user.id'),
            'created_by' => Arr::get($row, 'user.id'),
            'updated_by' => Arr::get($row, 'user.id'),
            'status' => Arr::get($row, 'status'),
            'seo.keyword' => Arr::get($row, 'keyword'),
            'seo.description' => Arr::get($row, 'description'),
        ];
    }

}
