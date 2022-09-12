<?php

namespace App\Models\Services;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Models\Entities\CommentEntity;
use App\Concerns\Databases\Service;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CommentService
 *
 * @package App\Models\Services
 * @Author: Roy
 * @DateTime: 2021/8/14 上午 11:04
 */
class CommentService extends Service
{
    /**
     * @return \Illuminate\Database\Eloquent\Model
     * @Author: Roy
     * @DateTime: 2022/8/6 下午 12:59
     */
    protected function getEntity(): Model
    {
        if (app()->has(CommentEntity::class) === false) {
            app()->singleton(CommentEntity::class);
        }

        return app(CommentEntity::class);
    }

    /**
     * @Author: Roy
     * @DateTime: 2021/8/14 上午 11:13
     */
    public function getCommentsByArticleId()
    {
        return $this->getEntity()
            ->where('article_id', $this->getRequestByKey('id'))
            ->get();
    }

    /**
     * @return mixed
     * @throws \Throwable
     * @Author: Roy
     * @DateTime: 2021/8/14 上午 11:06
     */
    public function createComment()
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
    public function updateComment()
    {
        $Entity = $this->getEntity()
            ->find($this->getRequestByKey('id'));

        if (is_null($Entity)) {
            return null;
        }

        $UpdateData = $this->getRequestByKey(CommentEntity::Table);
        # 塞log
        $logs = $Entity->logs;
        $logs[] = [
            'content'    => Arr::get($UpdateData, 'content'),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ];
        Arr::set($UpdateData, 'logs', $logs);
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
