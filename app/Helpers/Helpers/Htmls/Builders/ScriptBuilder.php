<?php

namespace App\Helpers\Htmls\Builders;

use App\Helpers\Htmls\Interfaces\HtmlInterface;
use Uuid;

class ScriptBuilder implements HtmlInterface
{

    public $CurrentTemplateName;

    private $TemplateAttributeMap = [
        // <script src="{Uri}"></script>
        'js'      => [],
        // <script src="{Uri}" async="async"></script>
        'asyncjs' => ['async' => 'async'],
        // <script src="{Uri}" defer="defer"></script>
        'deferjs' => ['defer' => 'defer'],
    ];

    // 蒐集所有歸類的資訊源
    private $OriginalMap;

    private $BuilderMap;

    private $ScriptIdPrefix = 'php_';

    private $DynamicLoaderCode = <<<SCRIPT_CODE
<script>
(function() {
    function createScript(e,t,r){if(r=0|r,void 0==e[r])return void(t&&t());var i=document.createElement("script");i.setAttribute("src",e[r].src),e[r].id&&i.setAttribute("id",e[r].id),e[r].async&&i.setAttribute("async",e[r].async),e[r].defer&&i.setAttribute("defer",e[r].defer);var c=function(){r+=1,createScript(e,t,r)};return i.onload=c,i.onerror=c,document.querySelector("#"+e[r].id)?void c():void document.querySelector("body").appendChild(i)}
    createScript(%s);
})();
</script>
SCRIPT_CODE;

    /**
     * [__construct description]
     * @Author    Boday
     * @DateTime  2017-09-15T08:57:22+0800
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
     * @DateTime  2017-09-15T08:57:39+0800
     * @return    [type]                    [description]
     */
    public function getTemplateAttributeMap()
    {
        return $this->TemplateAttributeMap;
    }

    /**
     * [setCurrentTemplateName description]
     * @Author    Boday
     * @DateTime  2017-09-15T08:57:50+0800
     * @param     [type]                    $Name  [description]
     */
    public function setCurrentTemplateName($Name)
    {
        $this->CurrentTemplateName = $Name;
    }

    /**
     * [resetCurrentTemplateName description]
     * @Author    Boday
     * @DateTime  2017-09-15T08:58:01+0800
     * @return    [type]                    [description]
     */
    public function resetCurrentTemplateName()
    {
        $this->CurrentTemplateName = null;
    }

    /**
     * [transformAttribute description]
     * @Author    Boday
     * @DateTime  2017-09-15T08:58:17+0800
     * @return    [type]                    [description]
     */
    public function transformAttribute()
    {
        // 把上次的屬性清除
        unset($this->Attribute);

        $Src       = func_get_arg(0);
        $DefaultId = $this->ScriptIdPrefix . Uuid::generate(5, $Src, Uuid::NS_DNS);

        // 如果不屬於目前樣板的項目
        if (is_null($this->TemplateAttributeMap->get($this->CurrentTemplateName))) {

            // 如果第二個參數有問題
            if (func_num_args() > 1 && is_array(func_get_arg(1)) == false) {
                throwException(HtmlException::ATTRIBUTE_NOT_ARRAY);
                return $this;
            }

            $this->Attribute = ['src' => $Src, 'id' => $DefaultId];
            if ((func_num_args() > 1)) {

                $Arguments = func_get_arg(1);

                $Arguments['id'] = (empty($Arguments['id']) || is_string($Arguments['id']) == false) ? $DefaultId : $this->ScriptIdPrefix . $Arguments['id'];
                $this->Attribute = array_merge(['src' => $Src], $Arguments);
            }
            return $this;
        }

        // 有指定樣式
        $Id = (func_num_args() > 1) ? func_get_arg(1) : null;
        $Id = (empty($Id) || is_string($Id) == false) ? $DefaultId : $this->ScriptIdPrefix . $Id;

        switch ($this->CurrentTemplateName) {
            case 'js':
                $this->Attribute = ['src' => $Src, 'id' => $Id];
                break;
            case 'asyncjs':
            case 'deferjs':
                $this->Attribute = array_merge(['src' => $Src, 'id' => $Id], $this->TemplateAttributeMap->get($this->CurrentTemplateName));
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
     * @DateTime  2017-09-15T14:42:44+0800
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
                return sprintf('<script %s/></script>', $Content);
            })->all()
        );
    }

    /**
     * [dynamicloader description]
     * @Author    Boday
     * @DateTime  2017-09-18T09:11:07+0800
     * @return    [type]                    [description]
     */
    public function dynamicloader()
    {

        if (empty($OriginalMap = $this->OriginalMap)) {
            return;
        }

        return sprintf($this->DynamicLoaderCode,
            json_encode(
                $this->OriginalMap->values()->all()
            )
        );
    }

}
