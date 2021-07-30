<?php

namespace App\Helpers\Metas;

class MetaGenerator
{
    /**
     * [getAttribute description]
     * @Author    Boday
     * @DateTime  2017-08-30T14:05:29+0800
     * @param     [type]                    $Name   [description]
     * @param     [type]                    $Value  [description]
     * @return    [type]                            [description]
     */
    public function getAttribute($Name, $Value)
    {
        if (empty($Name) || empty($Value)) {
            return [];
        }
        return [$Name => $Value];
    }

    /**
     * [getAttributelist description]
     * @Author    Boday
     * @DateTime  2017-08-30T14:23:16+0800
     * @return    [type]                    [description]
     */
    public function getAttributelist()
    {
        $Args = collect(func_get_args())->filter()->toarray();
        return count($Args) > 1 ? call_user_func_array('array_merge', $Args) : $Args['0'];
    }

    /**
     * [getHtml description]
     * @Author    Boday
     * @DateTime  2017-08-31T14:53:34+0800
     * @param     [type]                    $BuilderDataBag  [description]
     * @return    [type]                                     [description]
     */
    public function getHtml($BuilderDataBag = null)
    {
        if (empty($BuilderDataBag)) {
            return;
        }
        return $BuilderDataBag->map(function ($Attribs) {
            $Content = array_reduce(array_keys($Attribs), function ($Carry, $Key) use ($Attribs) {
                return $Carry . sprintf('%s="%s" ', $Key, htmlspecialchars($Attribs[$Key]));
            });
            return sprintf('<meta %s/>', $Content);
        });
    }

}
