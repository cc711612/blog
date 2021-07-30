<?php

namespace App\Concerns\Databases;

use App\Concerns\Databases\Contracts\Services\ServiceV2 as ServiceContracts;

/**
 * Class ServiceV2
 *
 * @package App\Concerns\Databases
 * @Author  : daniel
 * @DateTime: 2020/10/5 10:47 上午
 */
abstract class ServiceV2 implements ServiceContracts
{
    /**
     * @var null
     * @Author  : daniel
     * @DateTime: 2020/10/5 10:47 上午
     */
    protected $admin_id = null;
    /**
     * @var int
     * @Author  : daniel
     * @DateTime: 2020/10/5 10:47 上午
     */
    protected $page_count = 30;

    /**
     * @return int
     * @Author  : daniel
     * @DateTime: 2020/10/5 10:47 上午
     */
    public function getPageCount(): int
    {
        return $this->page_count;
    }

    /**
     * @param int $page_count
     *
     * @return $this
     * @Author  : daniel
     * @DateTime: 2020/10/5 10:47 上午
     */
    public function setPageCount(int $page_count): ServiceContracts
    {
        $this->page_count = $page_count;
        return $this;
    }

    /**
     * @return \App\Concerns\Databases\Contracts\Services\ServiceV2
     * @Author  : daniel
     * @DateTime: 2020/10/5 10:47 上午
     */
    public static function getInstance(): ServiceContracts
    {
        if (app()->has(static::class) === false) {
            app()->singleton(static::class);
        }

        return app(static::class);
    }
}
