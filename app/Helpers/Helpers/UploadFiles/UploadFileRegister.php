<?php

namespace App\Helpers\UploadFiles;

use App\Helpers\UploadFiles\UploadFileManager;

class UploadFileRegister
{

    protected $UploadFileManager;

    public function __construct(UploadFileManager $UploadFileManager)
    {
        $this->UploadFileManager = $UploadFileManager;
    }

    /**
     * [__call description]
     * @Author    Boday
     * @DateTime  2017-11-16T15:40:21+0800
     * @param     [type]                    $name       [description]
     * @param     [type]                    $arguments  [description]
     * @return    [type]                                [description]
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->UploadFileManager, $name], $arguments);
    }

}
