<?php

namespace App\Helpers\Htmls;

use App\Helpers\Htmls\Builders\LinkBuilder;
use App\Helpers\Htmls\Builders\ScriptBuilder;
use App\Helpers\Htmls\Exceptions\HtmlException;

class HtmlDirector
{

    protected $Builder;

    private $CurrentBuilder;

    private $PassMethodName = [];

    /**
     * [__construct description]
     * @Author    Boday
     * @DateTime  2017-09-14T09:30:05+0800
     * @param     LinkBuilder               $LinkBuilder    [description]
     * @param     ScriptBuilder             $ScriptBuilder  [description]
     */
    public function __construct(LinkBuilder $LinkBuilder, ScriptBuilder $ScriptBuilder)
    {

        // 注入建造者
        $this->Builder = collect([
            'link'   => $LinkBuilder,
            'script' => $ScriptBuilder,
        ]);

        // 取得建造者樣板屬性 map
        foreach ($this->Builder as $BuilderName => $Builder) {
            $this->PassMethodName[$BuilderName] = $this->Builder->get($BuilderName)->getTemplateAttributeMap()->keys()->all();
        }
    }

    /**
     * [__call description]
     * @Author    Boday
     * @DateTime  2017-09-14T09:30:48+0800
     * @return    [type]                    [description]
     */
    public function __call($name, $arguments)
    {

        $Filter = array_filter($this->PassMethodName, function ($item) use ($name) {
            return in_array($name, $item);
        });

        // METHOD_NOT_EXISTS
        if (empty($Filter)) {
            throwException(HtmlException::METHOD_NOT_EXISTS);
            return;
        }

        // 設定當前的樣板名稱
        $Builder = key($Filter);
        $this->Builder->get($Builder)->setCurrentTemplateName($name);

        return $this;
    }

    /**
     * [getBuilders description]
     * @Author    Boday
     * @DateTime  2017-09-13T16:03:18+0800
     * @return    [type]                    [description]
     */
    public function getBuilders()
    {
        return $this->Builder;
    }

    /**
     * [link description]
     * @Author    Boday
     * @DateTime  2017-09-13T16:02:17+0800
     * @return    [type]                    [description]
     */
    public function link()
    {
        $this->CurrentBuilder = $this->Builder->get('link');
        $this->CurrentBuilder->resetCurrentTemplateName();
        return $this;
    }

    /**
     * [script description]
     * @Author    Boday
     * @DateTime  2017-09-13T16:02:20+0800
     * @return    [type]                    [description]
     */
    public function script()
    {
        $this->CurrentBuilder = $this->Builder->get('script');
        $this->CurrentBuilder->resetCurrentTemplateName();
        return $this;
    }

    public function assignConfig()
    {
        dd($this->CurrentBuilder);
    }

    /**
     * [append description]
     * @Author    Boday
     * @DateTime  2017-09-13T16:55:05+0800
     * @param     string                    $Sources    [description]
     * @param     array                     $Attribute  [description]
     * @return    [type]                                [description]
     */
    public function append(string $Uri)
    {
        call_user_func_array([$this->CurrentBuilder, 'transformAttribute'], func_get_args())->append();
        return $this;
    }

    /**
     * [prepend description]
     * @Author    Boday
     * @DateTime  2017-09-13T16:46:41+0800
     * @return    [type]                    [description]
     */
    public function prepend(string $Uri)
    {
        call_user_func_array([$this->CurrentBuilder, 'transformAttribute'], func_get_args())->prepend();
        return $this;
    }

    /**
     * [put description]
     * @Author    Boday
     * @DateTime  2017-09-14T15:16:48+0800
     * @param     int                       $Index  [description]
     * @param     string                    $Uri    [description]
     * @return    [type]                            [description]
     */
    public function put(int $Index, string $Uri)
    {
        $Arguments = func_get_args();
        array_forget($Arguments, 0);
        call_user_func_array([$this->CurrentBuilder, 'transformAttribute'], $Arguments)->put($Index);
        return $this;
    }

    /**
     * [after description]
     * @Author    Boday
     * @DateTime  2017-09-14T15:30:38+0800
     * @param     int                       $Index  [description]
     * @param     string                    $Uri    [description]
     * @return    [type]                            [description]
     */
    public function before(int $Index, string $Uri)
    {
        $Arguments = func_get_args();
        array_forget($Arguments, 0);
        call_user_func_array([$this->CurrentBuilder, 'transformAttribute'], $Arguments)->before($Index);
        return $this;
    }

    /**
     * [remove description]
     * @Author    Boday
     * @DateTime  2017-09-15T13:51:48+0800
     * @param     int                       $Index  [description]
     * @return    [type]                            [description]
     */
    public function remove(int $Index)
    {
        return $this->CurrentBuilder->remove($Index);
    }

    /**
     * [all description]
     * @Author    Boday
     * @DateTime  2017-09-14T15:42:23+0800
     * @return    [type]                    [description]
     */
    public function all()
    {
        return $this->CurrentBuilder->all();
    }

    /**
     * [html description]
     * @Author    Boday
     * @DateTime  2017-09-14T15:42:20+0800
     * @return    [type]                    [description]
     */
    public function html()
    {
        return $this->CurrentBuilder->html();
    }

    /**
     * [dynamicloader description]
     * @Author    Boday
     * @DateTime  2017-09-15T09:19:46+0800
     * @return    [type]                    [description]
     */
    public function dynamicloader()
    {
        return $this->CurrentBuilder->dynamicloader();
    }

}
