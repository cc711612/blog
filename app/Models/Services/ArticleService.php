<?php

namespace App\Models\Services;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Models\Entities\ArticleEntity;
use App\Models\Entities\UserEntity;
use App\Models\Entities\CommentEntity;
use App\Models\Entities\SocialEntity;
use App\Models\Supports\SocialType;
use App\Traits\CacheTrait;

/**
 * Class ArticleService
 *
 * @package App\Models\Services
 * @Author: Roy
 * @DateTime: 2021/8/11 下午 02:57
 */
class ArticleService
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
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @Author: Roy
     * @DateTime: 2021/8/11 下午 02:53
     */
    public function paginate(int $page_count = 10): LengthAwarePaginator
    {
        # 一頁幾個
        if (is_null($this->getRequestByKey('per_page')) === false) {
            $page_count = $this->getRequestByKey('per_page');
        }

        $Result = $this->getEntity()
            ->with([
                UserEntity::Table => function ($query) {
                    $query->select(['id', 'name', 'images']);
                },
            ])
            ->select(['id', 'user_id', 'title', 'content', 'status', 'updated_at']);
        # 作者
        if (is_null($this->getRequestByKey('user')) === false) {
            $Result = $Result->where('user_id', $this->getRequestByKey('user'));
        }

        return $Result
            ->where('status', 1)
            ->orderByDesc('updated_at')
            ->paginate($page_count);
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
                CommentEntity::Table => function ($query) {
                    $query
                        ->with([
                            UserEntity::Table => function ($query) {
                                $query->select(['id', 'name', 'images']);
                            },
                        ])
                        ->select(['id', 'user_id', 'article_id', 'content', 'logs', 'updated_at'])
                        ->orderBy('id');
                },
            ])
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

    /**
     * @param  int  $id
     *
     * @Author: Roy
     * @DateTime: 2022/3/16 下午 12:35
     */
    public function getArticleUserSocialByArticleId(int $id)
    {
        return $this->getEntity()
            ->with([
                UserEntity::Table => function ($query) {
                    return $query->with([
                        SocialEntity::Table => function ($querySocial) {
                            return $querySocial
                                ->where('social_type', SocialType::Line)
                                ->where('followed', 1)
                                ->whereNotNull('social_type_value')
                                ->first()
                            ;
                        },
                    ])
                        ->select(['id']);
                },
            ])
            ->select(['id', 'user_id'])
            ->whereHas(UserEntity::Table, function ($query) {
                return $query->whereHas(SocialEntity::Table, function ($querySocial) {
                    return $querySocial
                        ->where('social_type', SocialType::Line)
                        ->where('followed', 1)
                        ->whereNotNull('social_type_value');
                });
            })
            ->find($id);
    }
}
