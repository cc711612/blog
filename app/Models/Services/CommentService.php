<?php

namespace App\Models\Services;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Models\Entities\CommentEntity;

/**
 * Class CommentService
 *
 * @package App\Models\Services
 * @Author: Roy
 * @DateTime: 2021/8/14 上午 11:04
 */
class CommentService
{
    /**
     * @return \App\Models\Entities\CommentEntity
     * @Author: Roy
     * @DateTime: 2021/8/14 上午 11:04
     */
    private function getEntity(): CommentEntity
    {
        if (app()->has(CommentEntity::class) === false) {
            app()->singleton(CommentEntity::class);
        }

        return app(CommentEntity::class);
    }

    public function getRequest(): array
    {
        return $this->request;
    }

    /**
     * @param  string  $key
     *
     * @return mixed
     * @Author  : steatng
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
     * @Author  : steatng
     * @DateTime: 2021/4/19 下午5:55
     */
    public function setRequest(array $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @Author: Roy
     * @DateTime: 2021/8/14 上午 11:13
     */
    public function getCommentsByArticleId()
    {
        return $this->getEntity()
            ->where('article_id',$this->getRequestByKey('id'))
            ->get();
    }

    /**
     * @return mixed
     * @throws \Throwable
     * @Author: Roy
     * @DateTime: 2021/8/14 上午 11:06
     */
    public function create()
    {
        return DB::transaction(function () {
            $CommentEntity = $this->getEntity()
                ->create($this->getRequestByKey(CommentEntity::Table));
            return $CommentEntity;
        });
    }


    /**
     * @return |null
     * @Author: Roy
     * @DateTime: 2021/8/11 下午 02:34
     */
    public function update()
    {
        $Entity = $this->getEntity()
            ->find($this->getRequestByKey('id'));

        if (is_null($Entity)) {
            return null;
        }

        $UpdateData = $this->getRequestByKey(CommentEntity::Table);
        Arr::set($UpdateData,'logs',array_merge($Entity->logs,Arr::get($UpdateData,'content')));
        return $Entity->update($UpdateData);
    }

    /**
     * @return |null
     * @Author: Roy
     * @DateTime: 2021/8/14 下午 12:21
     */
    public function delete()
    {
        $Entity = $this->getEntity()
            ->find($this->getRequestByKey('id'));
        if (is_null($Entity)) {
            return null;
        }
        return $Entity->update($this->getRequestByKey(CommentEntity::Table));
    }

}
