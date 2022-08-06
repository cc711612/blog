<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\ApiPaginateTrait;
use App\Traits\ImageTrait;
use Illuminate\Support\Arr;
use App\Models\Entities\CommentEntity;
use App\Models\Entities\ArticleEntity;
use Illuminate\Support\Collection;

class ArticleApiResource extends JsonResource
{
    use ApiPaginateTrait, ImageTrait;

    /**
     * @return array
     * @Author: Roy
     * @DateTime: 2022/8/6 下午 12:52
     */
    public function paginate(): array
    {
        return [
            'paginate' => $this->handleApiPageInfo($this->resource),
            'articles' => $this->resource->getCollection()->map(function (ArticleEntity $articleEntity) {
                return [
                    'id'              => Arr::get($articleEntity, 'id'),
                    'title'           => Arr::get($articleEntity, 'title'),
                    'sub_title'       => $this->getShortContent(strip_tags(Arr::get($articleEntity, 'content')), 80,
                        '...'),
                    'preview_content' => $this->getShortContent(strip_tags(Arr::get($articleEntity, 'content')), 180),
                    'user'            => [
                        'id'    => Arr::get($articleEntity, 'users.id'),
                        'name'  => Arr::get($articleEntity, 'users.name'),
                        'image' => $this->handleUserImage(Arr::get($articleEntity, 'users.images.cover')),
                    ],
                    'updated_at'      => Arr::get($articleEntity, 'updated_at')->format('Y-m-d H:i:s'),
                ];
            }),
        ];
    }

    /**
     * @return array
     * @Author: Roy
     * @DateTime: 2022/8/6 下午 12:51
     */
    public function show(): array
    {
        return [
            'id'         => Arr::get($this->resource, 'id'),
            'title'      => Arr::get($this->resource, 'title'),
            'content'    => Arr::get($this->resource, 'content'),
            'user'       => [
                'id'           => Arr::get($this->resource, 'users.id'),
                'name'         => Arr::get($this->resource, 'users.name'),
                'introduction' => Arr::get($this->resource, 'users.introduction'),
                'image'        => $this->handleUserImage(Arr::get($this->resource, 'users.images.cover')),
            ],
            'updated_at' => Arr::get($this->resource, 'updated_at')->format('Y-m-d H:i:s'),
            'comments'   => $this->handleComment(Arr::get($this->resource, 'comments', collect([]))),
        ];
    }

    /**
     * @param  string  $string
     * @param  int  $limit
     * @param  string  $add
     *
     * @return string
     * @Author: Roy
     * @DateTime: 2022/8/6 下午 12:33
     */
    private function getShortContent(string $string, int $limit = 0, string $add = "")
    {
        return sprintf('%s%s',
            mb_substr(str_replace(["\r", "\n", "\r\n", "\n\r", PHP_EOL, "&nbsp"], '', $string), 0, $limit), $add);
    }

    /**
     * @param $Comments
     *
     * @return mixed
     * @Author: Roy
     * @DateTime: 2022/8/6 下午 12:51
     */
    private function handleComment(Collection $Comments): Collection
    {
        return $Comments->map(function (CommentEntity $comment) {
            return [
                'id'         => Arr::get($comment, 'id'),
                'user'       => [
                    'id'    => Arr::get($comment, 'users.id'),
                    'name'  => Arr::get($comment, 'users.name'),
                    'image' => $this->handleUserImage(Arr::get($comment, 'users.images.cover')),
                ],
                'content'    => Arr::get($comment, 'content'),
                'updated_at' => Arr::get($comment, 'updated_at')->format('Y-m-d H:i:s'),
                'logs'       => Arr::get($comment, 'logs', []),
            ];
        });
    }
}
