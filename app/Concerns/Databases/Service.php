<?php

namespace App\Concerns\Databases;

use App\Concerns\Databases\Contracts\Services\Service as ServiceContracts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * Class Service
 *
 * @package App\Concerns\Databases
 * @Author  : daniel
 * @DateTime: 2022/5/25 上午11:01
 */
abstract class Service implements ServiceContracts
{
    abstract protected function getEntity(): Model;

    /**
     * @var int
     * @Author  : daniel
     * @DateTime: 2022/5/25 上午11:01
     */
    protected $page_count = 50;

    protected $admin_id;

    /**
     * @var array
     * @Author  : daniel
     * @DateTime: 2022/5/25 上午11:01
     */
    private $request = [];

    /**
     * @return array
     * @Author  : daniel
     * @DateTime: 2022/5/25 上午11:01
     */
    public function getRequest(): array
    {
        return $this->request;
    }

    /**
     * @param  string  $key
     *
     * @return array|\ArrayAccess|mixed
     * @Author  : daniel
     * @DateTime: 2022/5/25 上午11:01
     */
    public function getRequestByKey(string $key)
    {
        return Arr::get($this->getRequest(), $key, null);
    }

    /**
     * @return mixed
     * @Author  : daniel
     * @DateTime: 2022/5/25 下午3:26
     */
    private function getAdminId()
    {
        return $this->admin_id;
    }

    /**
     * @param  int  $admin_id
     *
     * @return $this|\App\Concerns\Databases\Contracts\Services\Service
     * @Author  : daniel
     * @DateTime: 2022/5/30 上午11:17
     */
    public function setAdminId(int $admin_id): ServiceContracts
    {
        $this->admin_id = $admin_id;
        return $this;
    }


    /**
     * @param  array  $request
     *
     * @return $this
     * @Author  : daniel
     * @DateTime: 2022/5/25 上午11:01
     */
    public function setRequest(array $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return bool
     * @Author  : daniel
     * @DateTime: 2022/5/25 上午11:01
     */
    public function isEmptyRequest()
    {
        return empty($this->getRequest());
    }

    /**
     * @return int
     * @Author  : daniel
     * @DateTime: 2022/5/25 上午11:01
     */
    public function getPageCount(): int
    {
        return $this->page_count;
    }

    /**
     * @param  int  $page_count
     *
     * @return $this|\App\Concerns\Databases\Contracts\Services\Service
     * @Author  : daniel
     * @DateTime: 2022/5/25 上午11:01
     */
    public function setPageCount(int $page_count): ServiceContracts
    {
        $this->page_count = $page_count;
        return $this;
    }

    /**
     * @return \App\Concerns\Databases\Contracts\Services\Service
     * @Author  : daniel
     * @DateTime: 2022/5/25 上午11:01
     */
    public static function getInstance(): ServiceContracts
    {
        if (app()->has(static::class) === false) {
            app()->singleton(static::class);
        }

        return app(static::class);
    }

    /**
     * @param  int  $id
     * @param  array  $update
     *
     * @return bool
     * @Author  : daniel
     * @DateTime: 2022/6/2 上午10:24
     */
    public function update(int $id, array $update): bool
    {
        $Entity = $this->getEntity()->find($id);

        if ($Entity === null) {
            throw new \InvalidArgumentException('你輸入的id找不到任何對應的資料');
        }

        return $Entity->update($update);
    }

    /**
     * @param  int  $id
     *
     * @return mixed
     * @Author: Roy
     * @DateTime: 2022/6/17 下午 04:29
     */
    public function find(int $id)
    {
        return $this->getEntity()->find($id);
    }

    /**
     * @param  array  $create
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @Author: Roy
     * @DateTime: 2022/6/17 下午 04:29
     */
    public function create(array $create): Model
    {
        return $this->getEntity()->create($create);
    }
}
