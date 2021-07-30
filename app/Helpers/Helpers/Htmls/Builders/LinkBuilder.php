<?php

namespace App\Helpers\Htmls\Builders;

use App\Helpers\Htmls\Exceptions\HtmlException;
use App\Helpers\Htmls\Interfaces\HtmlInterface;

class LinkBuilder implements HtmlInterface
{

    public $CurrentTemplateName;

    private $TemplateAttributeMap = [
        // <link href="{Uri}" rel="icon" type="image/ico" />
        'icon'       => ['rel' => 'icon', 'type' => 'image/ico'],
        // <link href="{Uri}" rel="apple-touch-icon-precomposed" sizes="{Size}" />
        'touchicon'  => ['rel' => 'apple-touch-icon-precomposed'],
        // <link href="{Uri}" rel="canonical" />
        'canonical'  => ['rel' => 'canonical'],
        // <link href="{Uri}" rel="alternate" type="application/atom+xml"  title="{Title}" >
        'atom'       => ['rel' => 'alternate', 'type' => 'application/atom+xml'],
        // <link href="{Uri}" rel="alternate" type="application/rss+xml"  title="{Title}" >
        'rss'        => ['rel' => 'alternate', 'type' => 'application/rss+xml'],
        // <link href="{Uri}" rel="first|last|prev|next|up"  title="first|last|prev|next|up" >
        'pagination' => ['rel' => 'first|last|prev|next|up', 'title' => 'first|last|prev|next|up'],
        // <link href="{Uri}" rel="stylesheet" type="text/css" media="screen" />
        'css'        => ['rel' => 'stylesheet', 'type' => 'text/css', 'media' => 'screen'],
    ];

    // 蒐集所有歸類的資訊源
    private $OriginalMap;

    private $BuilderMap;

    /**
     * [__construct description]
     * @Author    Boday
     * @DateTime  2017-09-14T08:54:58+0800
     */
    public function __construct()
    {
        // 轉成集合
        $this->TemplateAttributeMap = collect($this->TemplateAttributeMap);
        $this->OriginalMap          = collect($this->OriginalMap);
        $this->BuilderMap           = collect($this->BuilderMap);
    }

    /**
     * [getTemplateAttributeMap description]
     * @Author    Boday
     * @DateTime  2017-09-14T09:56:04+0800
     * @return    [type]                    [description]
     */
    public function getTemplateAttributeMap()
    {
        return $this->TemplateAttributeMap;
    }

    /**
     * [setCurrentTemplateName description]
     * @Author    Boday
     * @DateTime  2017-09-14T10:48:28+0800
     * @param     string                    $Name  [description]
     */
    public function setCurrentTemplateName($Name)
    {
        $this->CurrentTemplateName = $Name;
    }

    /**
     * [resetCurrentTemplateName description]
     * @Author    Boday
     * @DateTime  2017-09-14T14:45:30+0800
     * @return    [type]                    [description]
     */
    public function resetCurrentTemplateName()
    {
        $this->CurrentTemplateName = null;
    }

    /**
     * [transformAttribute description]
     * @Author    Boday
     * @DateTime  2017-09-14T11:40:57+0800
     * @return    [type]                    [description]
     */
    public function transformAttribute()
    {
        // 把上次的屬性清除
        unset($this->Attribute);

        // 如果不屬於目前樣板的項目
        if (is_null($this->TemplateAttributeMap->get($this->CurrentTemplateName))) {

            // 缺少第二個參數
            if (func_num_args() < 2) {
                throwException(HtmlException::ATTRIBUTE_NOT_ENOUGH);
                return $this;
            }

            // 如果第二個參數有問題
            if (is_array(func_get_arg(1)) == false) {
                throwException(HtmlException::ATTRIBUTE_NOT_ARRAY);
                return $this;
            }

            $this->Attribute = array_merge(['href' => func_get_arg(0)], func_get_arg(1));
            return $this;
        }

        switch ($this->CurrentTemplateName) {
            case 'icon':
            case 'canonical':
            case 'css':
                $this->Attribute = array_merge(['href' => func_get_arg(0)], $this->TemplateAttributeMap->get($this->CurrentTemplateName));
                break;
            case 'touchicon':
                if (func_num_args() < 2) {
                    throwException(HtmlException::ATTRIBUTE_NOT_ENOUGH);
                    return $this;
                }
                $this->Attribute = array_merge(['href' => func_get_arg(0)], $this->TemplateAttributeMap->get($this->CurrentTemplateName), ['sizes' => func_get_arg(1)]);
            case 'atom':
            case 'rss':
                if (func_num_args() < 2) {
                    throwException(HtmlException::ATTRIBUTE_NOT_ENOUGH);
                    return $this;
                }
                $this->Attribute = array_merge(['href' => func_get_arg(0)], $this->TemplateAttributeMap->get($this->CurrentTemplateName), ['title' => func_get_arg(1)]);
                break;
            case 'pagination':
                if (func_num_args() < 2) {
                    throwException(HtmlException::ATTRIBUTE_NOT_ENOUGH);
                    return $this;
                }
                dd(func_get_arg(1));

                $this->Attribute = ['href' => func_get_arg(0), 'rel' => strtolower(func_get_arg(1)), 'title' => strtolower(func_get_arg(1))];
                break;
        }

        return $this;
    }

    /**
     * [append description]
     * @Author    Boday
     * @DateTime  2017-09-14T15:11:16+0800
     * @return    [type]                    [description]
     */
    public function append()
    {
        if (empty($this->Attribute)) {
            return;
        }

        $this->OriginalMap->push($this->Attribute);
    }

    /**
     * [prepend description]
     * @Author    Boday
     * @DateTime  2017-09-14T15:13:56+0800
     * @return    [type]                    [description]
     */
    public function prepend()
    {
        if (empty($this->Attribute)) {
            return;
        }

        $this->OriginalMap->prepend($this->Attribute);
    }

    /**
     * [put description]
     * @Author    Boday
     * @DateTime  2017-09-14T15:19:16+0800
     * @param     [type]                    $Index  [description]
     * @return    [type]                            [description]
     */
    public function put($Index)
    {
        if (empty($this->Attribute)) {
            return;
        }

        $this->OriginalMap->put($Index, $this->Attribute);
    }

    /**
     * [before description]
     * @Author    Boday
     * @DateTime  2017-09-14T15:31:42+0800
     * @param     [type]                    $Index  [description]
     * @return    [type]                            [description]
     */
    public function before($Index)
    {
        if (empty($this->Attribute)) {
            return;
        }

        $Chunk             = $this->OriginalMap->splice($Index);
        $this->OriginalMap = $this->OriginalMap->merge([$this->Attribute]);
        $this->OriginalMap = $this->OriginalMap->merge($Chunk);
    }

    /**
     * [remove description]
     * @Author    Boday
     * @DateTime  2017-09-15T14:42:39+0800
     * @param     [type]                    $Index  [description]
     * @return    [type]                            [description]
     */
    public function remove($Index)
    {
        if (empty($this->OriginalMap)) {
            return;
        }
        $this->OriginalMap->forget($Index);
    }

    /**
     * [all description]
     * @Author    Boday
     * @DateTime  2017-09-14T15:11:12+0800
     * @return    [type]                    [description]
     */
    public function all()
    {
        if (empty($this->OriginalMap)) {
            return;
        }
        return $this->OriginalMap;
    }

    /**
     * [html description]
     * @Author    Boday
     * @DateTime  2017-09-14T15:42:43+0800
     * @return    [type]                    [description]
     */
    public function html()
    {
        if (empty($OriginalMap = $this->OriginalMap)) {
            return;
        }

        return join(PHP_EOL,
            $OriginalMap->map(function ($Attribs) {
                $Content = array_reduce(array_keys($Attribs), function ($Carry, $Key) use ($Attribs) {
                    return $Carry . sprintf('%s="%s" ', $Key, htmlspecialchars($Attribs[$Key]));
                });
                return sprintf('<link %s/>', $Content);
            })->all()
        );
    }

    /**
     * [dynamicloader description]
     * @Author    Boday
     * @DateTime  2017-09-18T09:11:15+0800
     * @return    [type]                    [description]
     */
    public function dynamicloader()
    {
        return;
    }

}
