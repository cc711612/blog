<?php
namespace App\Helpers\HttpUri\Facades;

use Illuminate\Support\Facades\Facade;

class HttpUriFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'HttpUriRegister';
    }
}
