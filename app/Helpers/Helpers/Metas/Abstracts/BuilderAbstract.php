<?php

namespace App\Helpers\Metas\Abstracts;

abstract class BuilderAbstract
{
    protected $Attributes;
    protected $Generator;

    /**
     * [get description]
     * @Author    Boday
     * @DateTime  2017-08-30T10:26:31+0800
     * @param     [type]                    $MainAttributeValue  [description]
     * @return    [type]                          [description]
     */
    public function get($MainAttributeValue)
    {
        if (empty($this->BuilderDataBag->get('Attribute'))) {
            return;
        }
        return $this->BuilderDataBag->get('Attribute')->get($MainAttributeValue);
    }

    /**
     * [all description]
     * @Author    Boday
     * @DateTime  2017-08-30T11:25:13+0800
     * @return    [type]                    [description]
     */
    public function all()
    {
        if (empty($this->BuilderDataBag->get('Attribute'))) {
            return;
        }
        return $this->BuilderDataBag->all();
    }

    /**
     * [html description]
     * @Author    Boday
     * @DateTime  2017-08-31T15:38:00+0800
     * @return    [type]                    [description]
     */
    public function html()
    {
        if (empty($this->BuilderDataBag->get('Attribute'))) {
            return;
        }
        return $this->Generator->getHtml($this->BuilderDataBag->get('Attribute'));
    }
}
