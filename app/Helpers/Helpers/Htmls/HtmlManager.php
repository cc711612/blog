<?php

namespace App\Helpers\Htmls;

use App\Helpers\Htmls\Exceptions\HtmlException;
use HttpUriGenerator;
use Illuminate\Support\Facades\Config;

class HtmlManager
{

    private $HtmlDirector;

    private $MetaDataConfg;

    /**
     * [__construct description]
     * @Author    Boday
     * @DateTime  2017-09-13T11:37:23+0800
     * @param     HtmlDirector              $HtmlDirector  [description]
     */
    public function __construct(HtmlDirector $HtmlDirector)
    {
        $this->HtmlDirector = $HtmlDirector;
        $this->initConfig()->assignBuilder();
    }

    /**
     * [initConfig description]
     * @Author    Boday
     * @DateTime  2017-09-18T13:54:14+0800
     * @return    [type]                    [description]
     */
    public function initConfig()
    {

        $service_domain_flag = get_service_domain_flag();
        /**
         * 如果發現 沒有 service domain flag
         */
        if (empty($service_domain_flag)) {
            throwException(HtmlException::SERVICE_DOMAIN_FLAG_ERROR);
            return;
        }

        /**
         * 如果發現 沒有 metadata
         */
        if (Config::has('metadata') == false) {
            throwException(HtmlException::METADATA_ERROR);
            return;
        }

        $config_service_domain_flag = str_replace('{service_domain_flag}', $service_domain_flag, 'metadata.{service_domain_flag}');
        if (Config::has($config_service_domain_flag) == false) {
            throwException(HtmlException::SERVICE_DOMAIN_FLAG_ERROR);
            return;
        }

        $config_service_domain_flag_links = str_replace('{service_domain_flag}', $service_domain_flag, 'metadata.{service_domain_flag}.links');
        if (Config::has($config_service_domain_flag_links) == false) {
            throwException(HtmlException::METADATA_METAS_ERROR);
            return;
        }

        $config_service_domain_flag_scripts = str_replace('{service_domain_flag}', $service_domain_flag, 'metadata.{service_domain_flag}.scripts');
        if (Config::has($config_service_domain_flag_scripts) == false) {
            throwException(HtmlException::METADATA_METAS_ERROR);
            return;
        }

        $this->MetaDataConfg = array_only(Config::get($config_service_domain_flag), ['links', 'scripts']);

        return $this;
    }

    /**
     * [assignBuilder description]
     * @Author    Boday
     * @DateTime  2017-09-18T14:00:01+0800
     * @return    [type]                    [description]
     */
    public function assignBuilder()
    {
        foreach ($this->MetaDataConfg as $Builder => $Config) {
            $Builder = sprintf('%s_assign', substr($Builder, 0, -1));
            $this->$Builder($Config);
        }
    }

    /**
     * [link description]
     * @Author    Boday
     * @DateTime  2017-09-13T15:13:07+0800
     * @return    [type]                    [description]
     */
    public function link()
    {
        return $this->HtmlDirector->link();
    }

    /**
     * [link_assign description]
     * @Author    Boday
     * @DateTime  2017-09-18T14:58:23+0800
     * @param     [type]                    $Config  [description]
     * @return    [type]                             [description]
     */
    public function link_assign($Config)
    {
        foreach ($Config as $Template => $Rows) {
            if (empty($Rows)) {
                continue;
            }
            foreach ($Rows as $Attribute) {

                // 是否使用 http_uri 做版本控制
                if (isset($Attribute['http_uri']) && isset($Attribute['href'])) {
                    $Attribute['href'] = HttpUriGenerator::url($Attribute['href']);
                }
                switch ($Template) {
                    case 'icon':
                    case 'canonical':
                    case 'css':
                        $this->link()->$Template()->append($Attribute['href']);
                        break;
                    case 'touchicon':
                        $this->link()->$Template()->append($Attribute['href'], $Attribute['sizes']);
                        break;
                    case 'atom':
                    case 'rss':
                    case 'pagination':
                        $this->link()->$Template()->append($Attribute['href'], $Attribute['title']);
                        break;
                    default:
                        $this->link()->append($Attribute['href'], $Attribute);
                        break;
                }
            }
        }
    }

    /**
     * [script description]
     * @Author    Boday
     * @DateTime  2017-09-13T15:13:04+0800
     * @return    [type]                    [description]
     */
    public function script()
    {
        return $this->HtmlDirector->script();
    }

    /**
     * [script_assign description]
     * @Author    Boday
     * @DateTime  2017-09-18T14:58:27+0800
     * @param     [type]                    $Config  [description]
     * @return    [type]                             [description]
     */
    public function script_assign($Config)
    {
        foreach ($Config as $Template => $Rows) {
            if (empty($Rows)) {
                continue;
            }
            foreach ($Rows as $Attribute) {

                // 是否使用 http_uri 做版本控制
                if (isset($Attribute['http_uri']) && isset($Attribute['src'])) {
                    $Attribute['src'] = HttpUriGenerator::url($Attribute['src']);
                }

                switch ($Template) {
                    case 'js':
                    case 'asyncjs':
                    case 'deferjs':
                        $this->script()->$Template()->append($Attribute['src'], $Attribute['id'] ?? false);
                        break;
                    default:
                        $this->script()->append($Attribute['src'], $Attribute);
                        break;
                }
            }

        }
    }

}
