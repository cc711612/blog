<?php

namespace App\Helpers\Trail\Facades;

use Illuminate\Support\Facades\Facade;

class TrailFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'TrailRegister';
    }
}
