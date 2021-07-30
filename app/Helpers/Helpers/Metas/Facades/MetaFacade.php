<?php
namespace App\Helpers\Metas\Facades;

use Illuminate\Support\Facades\Facade;

class MetaFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'MetaRegister';
    }
}
