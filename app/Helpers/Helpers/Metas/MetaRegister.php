<?php

namespace App\Helpers\Metas;

use App\Helpers\Metas\MetaManager;

class MetaRegister
{
    protected $MetaManager;

    /**
     * [__construct description]
     * @Author    Boday
     * @DateTime  2017-08-29T11:43:01+0800
     * @param     MetaManager               $MetaManager  [description]
     */
    public function __construct(MetaManager $MetaManager)
    {
        return $this->MetaManager = $MetaManager;
    }

    /**
     * [__call 因為不想破壞透過 {xxx}Register 的規則，又不想讓 MetaManager 都在這邊處理，然後順便保留點彈性]
     * @Author    Boday
     * @DateTime  2017-08-29T11:42:57+0800
     * @param     [type]                    $name       [description]
     * @param     [type]                    $arguments  [description]
     * @return    [type]                                [description]
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->MetaManager, $name], $arguments);
    }
}
