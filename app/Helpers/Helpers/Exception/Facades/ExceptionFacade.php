<?php

namespace App\Helpers\Exception\Facades;

use Illuminate\Support\Facades\Facade;

class ExceptionFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ExceptionRegister';
    }
}
