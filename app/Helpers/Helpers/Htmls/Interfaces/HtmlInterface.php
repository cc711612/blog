<?php
namespace App\Helpers\Htmls\Interfaces;

interface HtmlInterface
{
    public function getTemplateAttributeMap();
    public function setCurrentTemplateName($Name);
    public function resetCurrentTemplateName();

    public function transformAttribute();

    public function append();
    public function prepend();
    public function put($Index);
    public function before($Index);
    public function remove($Index);

    public function all();
    public function html();
    public function dynamicloader();
}
