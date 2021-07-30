<?php
namespace App\Helpers\Htmls\Facades;

use Illuminate\Support\Facades\Facade;

class HtmlFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'HtmlRegister';
    }
}
