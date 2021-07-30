<?php
namespace App\Helpers\UploadFiles\Facades;

use Illuminate\Support\Facades\Facade;

class UploadFileFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'UploadFileRegister';
    }
}
