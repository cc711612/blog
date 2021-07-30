<?php

namespace App\Helpers\Trail;

use App\Helpers\Trail\Exceptions\TrailException;
use App\Helpers\Trail\TrailFactory;
use Config;

class TrailManager
{

    const DEFAULT_FACTORY = 'default';
    private $CurrentFactory = 'default';

    private $TrailFactoryMap = [];

    /**
     * [__construct description]
     * @param \App\Helpers\Trail\TrailFactory $TrailFactory
     *
     * @throws \Exception
     * @Author    Boday
     * @DateTime  2017-09-05T09:19:22+0800
     */
    public function __construct(TrailFactory $TrailFactory)
    {
        $this->TrailFactoryMap = collect([
            self::DEFAULT_FACTORY => $TrailFactory,
        ]);

        // 進行初始化設定
        $this->init();
    }

    /**
     * [init description]
     * @throws \Exception
     * @Author    Boday
     * @DateTime  2017-09-05T10:46:14+0800
     */
    public function init()
    {

        $service_domain_flag = get_service_domain_flag();
        /**
         * 如果發現 沒有 service domain flag
         */
        if (empty($service_domain_flag)) {
            throwException(TrailException::SERVICE_DOMAIN_FLAG_ERROR);
            return;
        }

        /**
         * 如果發現 沒有 metadata
         */
        if (Config::has('metadata') == false) {
            throwException(TrailException::METADATA_ERROR);
            return;
        }

        $config_service_domain_flag = str_replace('{service_domain_flag}', $service_domain_flag, 'metadata.{service_domain_flag}');
        if (Config::has($config_service_domain_flag) == false) {
            throwException(TrailException::SERVICE_DOMAIN_FLAG_ERROR);
            return;
        }

        $config_service_domain_flag_title = str_replace('{service_domain_flag}', $service_domain_flag, 'metadata.{service_domain_flag}.title');
        if (Config::has($config_service_domain_flag_title) == false) {
            throwException(TrailException::METADATA_METAS_ERROR);
            return;
        }

        // 設定站台最初始的 Root 資料
        $this->name(self::DEFAULT_FACTORY)->setDefaultRoot(Config::get($config_service_domain_flag_title));
    }

    /**
     * [register description]
     * @Author    Boday
     * @DateTime  2017-09-07T11:13:04+0800
     * @param     string                    $NameSpace  [description]
     * @return    [type]                                [description]
     */
    public function register(string $NameSpace)
    {
        $this->TrailFactoryMap->put($NameSpace, clone $this->TrailFactoryMap->get(self::DEFAULT_FACTORY));
        return $this->TrailFactoryMap->get($NameSpace);
    }

    /**
     * [name description]
     * @Author    Boday
     * @DateTime  2017-09-07T11:09:25+0800
     * @param     string|self::DEFAULT_FACTORY     $NameSpace  [description]
     * @return    [type]                                       [description]
     */
    public function name(string $NameSpace = self::DEFAULT_FACTORY)
    {
        // 如果沒有指定的 Name 為空值
        if (empty($NameSpace)) {
            $NameSpace = self::DEFAULT_FACTORY;
        }

        if (empty($this->TrailFactoryMap->get($NameSpace))) {
            return $this->register($NameSpace);
        }
        return $this->TrailFactoryMap->get($NameSpace);
    }
}
