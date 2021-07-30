<?php

namespace App\Helpers\Trail;

use App\Helpers\Trail\TrailManager;

class TrailRegister
{

    private $TrailManager;

    /**
     * [__construct description]
     * @Author    Boday
     * @DateTime  2017-09-05T10:45:49+0800
     * @param     TrailManager              $TrailManager  [description]
     */
    public function __construct(TrailManager $TrailManager)
    {
        $this->TrailManager = $TrailManager;
    }

    /**
     * [__call 因為不想破壞透過 {xxx}Register 的規則，又不想讓 MetaManager 都在這邊處理，然後順便保留點彈性]
     * @Author    Boday
     * @DateTime  2017-09-07T10:58:39+0800
     * @param     [type]                    $name       [description]
     * @param     [type]                    $arguments  [description]
     * @return    [type]                                [description]
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->TrailManager, $name], $arguments);
    }

}
