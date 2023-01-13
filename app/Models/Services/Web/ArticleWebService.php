<?php

namespace App\Models\Services\Web;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Models\Entities\ArticleEntity;
use App\Models\Entities\UserEntity;
use App\Models\Entities\CommentEntity;
use const Grpc\STATUS_UNKNOWN;
use Illuminate\Support\Facades\Cache;
use App\Traits\CacheTrait;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class ArticleWebService
 *
 * @package App\Models\Services\Web
 * @Author: Roy
 * @DateTime: 2021/9/2 下午 10:56
 */
class ArticleWebService
{
    use CacheTrait;

    /**
     * @return \App\Models\Entities\ArticleEntity
     * @Author: Roy
     * @DateTime: 2021/8/11 下午 02:33
     */
    private function getEntity(): ArticleEntity
    {
        if (app()->has(ArticleEntity::class) === false) {
            app()->singleton(ArticleEntity::class);
        }

        return app(ArticleEntity::class);
    }

    public function getRequest(): array
    {
        return $this->request;
    }

    /**
     * @param  string  $key
     *
     * @return mixed
     * @Author  : Roy
     * @DateTime: 2021/4/19 下午5:55
     */
    private function getRequestByKey(string $key)
    {
        return Arr::get($this->getRequest(), $key, null);
    }

    /**
     * @param  array  $request
     *
     * @return $this
     * @Author  : Roy
     * @DateTime: 2021/4/19 下午5:55
     */
    public function setRequest(array $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @param  int  $page_count
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     * @Author: Roy
     * @DateTime: 2022/12/15 上午 11:25
     */
    public function paginate(int $page_count = 10): LengthAwarePaginator
    {
        $cache_key = $this->getArticleIndexCacheKey(Arr::get($this->getRequest(), 'page', 1));
        if (Cache::has($cache_key) === true) {
            return Cache::get($cache_key);
        }
        $Result = $this->getEntity()
            ->with([
                UserEntity::Table => function ($query) {
                    $query->select(['id', 'name', 'images']);
                },
            ])
            ->select(['id', 'user_id', 'title', 'content', 'status', 'updated_at', 'created_at']);
        # 作者
        if (is_null($this->getRequestByKey('user')) === false) {
            $Result = $Result->where('user_id', $this->getRequestByKey('user'));
        }

        $Result = $Result
            ->where('status', 1)
            ->WebArticle()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate($page_count);

        if ($Result->getCollection()->isNotEmpty() === true) {
            Cache::add($cache_key, $Result, 604800);
        }

        return $Result;
    }

    /**
     * @return mixed
     * @throws \Throwable
     * @Author: Roy
     * @DateTime: 2021/8/11 下午 02:34
     */
    public function create()
    {
        $this->forgetArticleIndexCache(1);
        return DB::transaction(function () {
            $ArticleEntity = $this->getEntity()
                ->create($this->getRequestByKey(ArticleEntity::Table));

            return $ArticleEntity;
        });
    }

    /**
     * @param  int  $id
     *
     * @return mixed
     * @Author: Roy
     * @DateTime: 2021/5/19 上午 10:48
     */
    public function find(int $id)
    {
        return $this->getEntity()
            ->with([
                UserEntity::Table    => function ($query) {
                    $query->select(['id', 'name', 'images', 'introduction']);
                },
//                CommentEntity::Table => function ($query) {
//                    $query
//                        ->with([
//                            UserEntity::Table => function ($query) {
//                                $query->select(['id', 'name', 'images']);
//                            },
//                        ])
//                        ->select(['id', 'user_id', 'article_id', 'content', 'logs', 'updated_at'])
//                        ->orderBy('id');
//                },
            ])
            ->where('status', 1)
            ->WebArticle()
            ->find($id);
    }

    /**
     * @return |null
     * @Author: Roy
     * @DateTime: 2021/8/11 下午 02:34
     */
    public function update()
    {
        $Entity = $this->getEntity()
            ->find($this->getRequestByKey(sprintf("%s.%s", ArticleEntity::Table, 'id')));

        if (is_null($Entity)) {
            return null;
        }
        return $Entity->update($this->getRequestByKey(ArticleEntity::Table));
    }

    /**
     * @param  array  $ids
     *
     * @return mixed
     * @Author: Roy
     * @DateTime: 2021/5/19 下午 02:58
     */
    public function getByIds(array $ids)
    {
        return $this->getEntity()
            ->MainActivity()
            ->select(['id'])
            ->whereIn('id', $ids)
            ->orderByDesc('sort')
            ->get();
    }

}
