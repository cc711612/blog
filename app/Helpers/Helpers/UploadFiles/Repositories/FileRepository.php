<?php

namespace App\Helpers\UploadFiles\Repositories;

use App\Concerns\Databases\Repository;
use App\Helpers\UploadFiles\Entities\Image;

/**
 * Class FileRepository
 *
 * @package App\Helpers\UploadFiles\Repositories
 * @Author: Roy
 * @DateTime: 2021/2/17 下午 06:08
 */
class FileRepository extends Repository
{
    /**
     * @var string
     * @Author  : daniel
     * @DateTime: 2020-05-14 17:07
     */
    protected $entity_class_name = Image::class;

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @throws \ReflectionException
     * @Author  : daniel
     * @DateTime: 2020-05-14 17:07
     */
    public function getBuilder()
    {
        return $this->getFinalBuilder();
    }

}
