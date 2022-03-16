<?php

namespace App\Models\Services;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Models\Entities\CommentEntity;
use App\Models\Entities\SocialEntity;
use App\Models\Supports\SocialType;

/**
 * Class SocialService
 *
 * @package App\Models\Services
 * @Author: Roy
 * @DateTime: 2021/8/20 下午 02:57
 */
class SocialService
{
    /**
     * @return \App\Models\Entities\SocialEntity
     * @Author: Roy
     * @DateTime: 2021/8/20 下午 02:57
     */
    private function getEntity(): SocialEntity
    {
        if (app()->has(SocialEntity::class) === false) {
            app()->singleton(SocialEntity::class);
        }

        return app(SocialEntity::class);
    }

    public function getRequest(): array
    {
        return $this->request;
    }

    /**
     * @param  string  $key
     *
     * @return array|\ArrayAccess|mixed
     * @Author: Roy
     * @DateTime: 2021/8/20 下午 02:58
     */
    private function getRequestByKey(string $key)
    {
        return Arr::get($this->getRequest(), $key, null);
    }

    /**
     * @param  array  $request
     *
     * @return $this
     * @Author: Roy
     * @DateTime: 2021/8/20 下午 02:58
     */
    public function setRequest(array $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return mixed
     * @Author: Roy
     * @DateTime: 2021/9/3 上午 01:27
     */
    public function findFaceBookEmail()
    {
        return $this->getEntity()
            ->where('email', $this->getRequestByKey('socials.email'))
            ->where('social_type', SocialType::Facebook)
            ->get()
            ->first();
    }

    /**
     * @return mixed
     * @Author: Roy
     * @DateTime: 2021/9/3 上午 01:27
     */
    public function findLineEmail()
    {
        return $this->getEntity()
            ->where('email', $this->getRequestByKey('socials.email'))
            ->where('social_type', SocialType::Line)
            ->get()
            ->first();
    }

    /**
     * @return mixed
     * @throws \Throwable
     * @Author: Roy
     * @DateTime: 2021/8/20 下午 02:58
     */
    public function create()
    {
        return DB::transaction(function () {
            $Entity = $this->getEntity()
                ->create($this->getRequestByKey(SocialEntity::Table));
            return $Entity;
        });
    }


    /**
     * @return |null
     * @Author: Roy
     * @DateTime: 2021/9/3 上午 01:27
     */
    public function update()
    {
        $Entity = $this->getEntity()
            ->find($this->getRequestByKey('id'));
        if (is_null($Entity)) {
            return null;
        }
        return $Entity->update($this->getRequestByKey(SocialEntity::Table));
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
        return $Entity->update($this->getRequestByKey(SocialEntity::Table));
    }

    /**
     * @return |null
     * @Author: Roy
     * @DateTime: 2022/3/16 上午 10:37
     */
    public function updateOrCreate()
    {
        if (is_null($this->getRequestByKey('social_type_value')) === true) {
            return null;
        }

        $Entity = $this->getEntity()
            ->where('social_type_value', $this->getRequestByKey('social_type_value'))
            ->where('social_type', SocialType::Line)
            ->first();

        if (is_null($Entity) === true) {
            return $this->getEntity()->create($this->getRequest());
        }
        return $Entity->update($this->getRequest());
    }
}
