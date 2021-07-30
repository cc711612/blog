<?php

namespace App\Helpers\Metas\Builders;

use App\Helpers\Metas\Abstracts\BuilderAbstract;
use App\Helpers\Metas\Interfaces\BuilderInterface;
use App\Helpers\Metas\MetaGenerator as Generator;

class HttpequivBuilder extends BuilderAbstract implements BuilderInterface
{

    protected $BuilderDataBag = [
        'MainAttributeName' => 'http-equiv',
        'SubAttributeName'  => 'content',
        'Attribute'         => [],
    ];

    /**
     * [__construct description]
     * @Author    Boday
     * @DateTime  2017-08-29T15:47:52+0800
     */
    public function __construct(Generator $Generator)
    {
        $this->Generator = $Generator;

        $this->BuilderDataBag = collect($this->BuilderDataBag);
        $this->Attributes     = collect($this->Attributes);
    }

    /**
     * [set description]
     * @Author    Boday
     * @DateTime  2017-08-30T10:44:18+0800
     * @param     [type]                    $MainAttributeValue       [description]
     * @param     [type]                    $SecondaryAttributeValue  [description]
     * @param     [type]                    $Arguments                [description]
     */
    public function set($MainAttributeValue, $SecondaryAttributeValue, $Arguments = null)
    {
        $this->Attributes->put(
            $MainAttributeValue,
            $this->Generator->getAttributelist(
                $this->Generator->getAttribute($this->BuilderDataBag->get('MainAttributeName'), $MainAttributeValue),
                $this->Generator->getAttribute($this->BuilderDataBag->get('SubAttributeName'), $SecondaryAttributeValue),
                $Arguments
            )
        );
        $this->BuilderDataBag->put('Attribute', $this->Attributes);
        return $this;
    }

}
