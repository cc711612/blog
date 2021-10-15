<?php

namespace App\Models\Services;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Models\UserEntity;

/**
 * Class UserService
 *
 * @package App\Models\Services
 * @Author: Roy
 * @DateTime: 2021/8/20 下午 04:14
 */
class UserService
{
    /**
     * @return \App\Models\Entities\SocialEntity
     * @Author: Roy
     * @DateTime: 2021/8/20 下午 02:57
     */
    private function getEntity(): UserEntity
    {
        if (app()->has(UserEntity::class) === false) {
            app()->singleton(UserEntity::class);
        }

        return app(UserEntity::class);
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

    public function findFaceBookEmail()
    {
        return $this->getEntity()
            ->where('email', $this->getRequestByKey('socials.email'))
            ->where('social_type', UserEntity::Facebook)
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
                ->create($this->getRequestByKey(UserEntity::Table));
            return $Entity;
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
        return $Entity->update($this->getRequestByKey(UserEntity::Table));
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
        return $Entity->update($this->getRequestByKey(UserEntity::Table));
    }

    /**
     * @param  string|null  $email
     *
     * @Author: Roy
     * @DateTime: 2021/8/20 下午 04:36
     */
    public function checkUserEmail(string $email = null)
    {

        return $this->getEntity()
            ->where('email', $email)
            ->get()
            ->first();
    }
}
