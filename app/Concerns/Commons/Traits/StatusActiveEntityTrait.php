<?php
namespace App\Concerns\Commons\Traits;

use App\Concerns\Databases\Contracts\Constants\RootStatus;
use App\Concerns\Databases\Contracts\Constants\Status;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Trait StatusActiveEntityTrait
 *
 * @package App\Concerns\Commons\Traits
 * @Author  : steatng
 * @DateTime: 2020/6/1 11:12
 */
trait StatusActiveEntityTrait
{
    /**
     * 将查询作用域限制为仅包含给定类型的用户。
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMainActivity($query)
    {
        return $query
            ->whereIn('status', [Status::STATUS_ENABLE,Status::STATUS_CRON]);
    }

    /**
     * 将查询作用域限制为仅包含给定类型的用户。
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMainActivityWithDate($query)
    {
        $now = Carbon::now();
        return $query
            ->where('status', Status::STATUS_ENABLE)
            ->whereRaw('? between start_at and end_at', [$now]);
    }

    /**
     * 将查询作用域限制为仅包含给定类型的用户。
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdminActivity($query)
    {
        return $query
            ->whereIn('status', [Status::STATUS_DISABLE,Status::STATUS_ENABLE,Status::STATUS_CRON]);
    }

    /**
     * 将查询作用域限制为仅包含给定类型的用户。
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDraft($query)
    {
        return $query
            ->whereIn('status', [Status::STATUS_DRAFT]);
    }

    /**
     * 将查询作用域限制为仅包含给定类型的用户。
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRootStatus($query)
    {
        return $query
            ->whereIn('root_status', [RootStatus::ROOT_STATUS_DISABLE]);
    }

    /**
     * @param $value
     *
     * @return null
     * @Author  : steatng
     * @DateTime: 2020/6/1 11:12
     */
    public function getStartAtAttribute($value)
    {
        return ($value == '1970-01-01 00:00:01' || empty($value)) ? null : Carbon::createFromFormat('Y-m-d H:i:s',$value);
    }

    /**
     * @param $value
     *
     * @Author  : steatng
     * @DateTime: 2020/6/1 11:12
     */
    public function setStartAtAttribute($value)
    {
        $this->attributes['start_at'] = (empty($value)) ? '1970-01-01 00:00:01' : $value;
    }

    /**
     * @param $value
     *
     * @return null
     * @Author  : steatng
     * @DateTime: 2020/6/1 11:12
     */
    public function getEndAtAttribute($value)
    {
        return ($value == '2038-01-19 03:14:07' || empty($value)) ? null : Carbon::createFromFormat('Y-m-d H:i:s',$value);
    }

    /**
     * @param $value
     *
     * @Author  : steatng
     * @DateTime: 2020/6/1 11:12
     */
    public function setEndAtAttribute($value)
    {
        $this->attributes['end_at'] = (empty($value)) ? '2038-01-19 03:14:07' : $value;
    }

    /**
     * @param $value
     *
     * @Author  : steatng
     * @DateTime: 2021/2/23 上午11:30
     */
    public function setCreatedByAttribute($value)
    {
        $this->attributes['created_by'] = $this->getCurrentLoginId($value);
    }

    /**
     * @param $value
     *
     * @Author  : steatng
     * @DateTime: 2021/2/23 上午11:30
     */
    public function setUpdatedByAttribute($value)
    {
        $this->attributes['updated_by'] = $this->getCurrentLoginId($value);
    }

    private function getCurrentLoginId($value): int
    {
        return empty($value) ? Auth::id() : $value;
    }
}
