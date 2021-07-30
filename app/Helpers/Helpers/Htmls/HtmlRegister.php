<?php

namespace App\Helpers\Htmls;

use App\Helpers\Htmls\HtmlManager;

class HtmlRegister
{
    protected $HtmlManager;

    /**
     * [__construct description]
     * @Author    Boday
     * @DateTime  2017-09-13T11:04:07+0800
     * @param     HtmlManager              $HtmlManager  [description]
     */
    public function __construct(HtmlManager $HtmlManager)
    {
        $this->HtmlManager = $HtmlManager;
    }

    /**
     * [__call 因為不想破壞透過 {xxx}Register 的規則，又不想讓 HtmlManager 都在這邊處理，然後順便保留點彈性]
     * @Author    Boday
     * @DateTime  2017-09-13T11:04:23+0800
     * @param     [type]                    $name       [description]
     * @param     [type]                    $arguments  [description]
     * @return    [type]                                [description]
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->HtmlManager, $name], $arguments);
    }
}
