<?php

namespace App\Concerns\Databases\Contracts\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Interface Service
 *
 * @package App\Databases\Contracts
 * @Author  : daniel
 * @DateTime: 2019-03-13 10:27
 */
interface Service
{
    /**
     * @param \App\Concerns\Databases\Contracts\Request|array $Request
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @Author  : daniel
     * @DateTime: 2019-03-20 14:13
     */
    public function create($Request) : Model;

    /**
     * @param int $id
     *
     * @return mixed
     * @Author  : daniel
     * @DateTime: 2019-03-18 15:56
     */
    public function find(int $id);

    /**
     * @param $id
     *
     * @return mixed
     * @Author  : daniel
     * @DateTime: 2019-12-24 11:14
     */
    public function findWithRepository(int $id);

    /**
     * @return \Illuminate\Support\Collection
     * @Author  : daniel
     * @DateTime: 2019-03-18 15:56
     */
    public function get() :Collection;

    /**
     * @param int                                   $id
     * @param \App\Concerns\Databases\Contracts\Request|array      $Request
     * @param \App\Concerns\Databases\Contracts\Request|array|null $LogRequest
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @Author  : daniel
     * @DateTime: 2019-03-20 15:43
     */
    public function update(int $id,$Request,$LogRequest = null):Model;

    /**
     * @param int $id
     *
     * @return bool|null
     * @Author  : daniel
     * @DateTime: 2020-05-21 11:21
     */
    public function delete(int $id);
}