<?php

namespace App\Helpers\UploadFiles\Services;

use App\Concerns\Databases\Contracts\Repository;
use App\Concerns\Databases\Contracts\Cache;
use App\Concerns\Databases\Contracts\Services\ServiceDraft;
use App\Concerns\Databases\Service;
use App\Helpers\UploadFiles\Repositories\FileRepository;

class FileService extends Service implements ServiceDraft
{
    protected $Repository;

    protected function getRepository(): Repository
    {
        if (app()->has(FileRepository::class) === false) {
            app()->singleton(FileRepository::class);
        }

        return app(FileRepository::class)
            ->setRelation([FileRepository::class => function($query){
            }]);
    }

    /**
     * @return \App\Concerns\Databases\Contracts\Cache
     * @Author  : daniel
     * @DateTime: 2020-05-13 17:46
     */
    protected function getCache(): Cache
    {
        // TODO: Implement getCache() method.
    }
}
