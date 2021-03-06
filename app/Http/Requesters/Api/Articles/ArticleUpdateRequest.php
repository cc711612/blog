<?php

namespace App\Http\Requesters\Api\Articles;

use App\Concerns\Databases\Request;
use Illuminate\Support\Arr;

class ArticleUpdateRequest extends Request
{
    /**
     * @return null[]
     * @Author  : Roy
     * @DateTime: 2020/12/15 下午 03:02
     */
    protected function schema(): array
    {
        return [
            'id'                       => null,
            'title'                    => null,
            'content'                  => null,
            'keyword'                  => null,
            'description'              => null,
            'updated_by'               => null,
            'status'                   => 1,
            'articles.title'           => null,
            'articles.content'         => null,
            'articles.updated_by'      => null,
            'articles.status'          => 1,
            'articles.seo.description' => null,
            'articles.seo.keyword'     => null,
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
            'id'                       => Arr::get($row, 'article'),
            'title'                    => Arr::get($row, 'title'),
            'content'                  => Arr::get($row, 'content'),
            'updated_by'               => Arr::get($row, 'user.id'),
            'status'                   => Arr::get($row, 'status'),
            'keyword'                  => Arr::get($row, 'keyword'),
            'description'              => Arr::get($row, 'description'),
            'articles.title'           => Arr::get($row, 'title'),
            'articles.content'         => Arr::get($row, 'content'),
            'articles.updated_by'      => Arr::get($row, 'user.id'),
            'articles.status'          => Arr::get($row, 'status'),
            'articles.seo.description' => Arr::get($row, 'description'),
            'articles.seo.keyword'     => Arr::get($row, 'keyword'),
        ];
    }

}
