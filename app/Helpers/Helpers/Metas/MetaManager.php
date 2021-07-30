<?php

namespace App\Helpers\Metas;

use App\Helpers\Metas\Builders\CharsetBuilder;
use App\Helpers\Metas\Builders\FacebookBuilder;
use App\Helpers\Metas\Builders\HttpequivBuilder;
use App\Helpers\Metas\Builders\ItempropBuilder;
use App\Helpers\Metas\Builders\StandardBuilder;
use App\Helpers\Metas\Builders\TwitterBuilder;
use App\Helpers\Metas\Exceptions\MetaException;
use App\Helpers\Metas\MetaClassify as Classify;
use Illuminate\Support\Facades\Config;

class MetaManager
{
    private $Config;

    private $DefaultSteup;

    private $Classify;

    private $Builder;

    /**
     * @var array
     * @Author  : boday
     * @DateTime: 2018/9/5 14:28
     */
    private $init_finish = [];

    /**
     * [__construct description]
     *
     * @Author    Boday
     * @DateTime  2017-08-29T11:46:28+0800
     *
     * @param     Classify  $Classify  [description]
     * @param     Generator $Generator [description]
     */
    public function __construct(
        Classify $Classify,
        CharsetBuilder $CharsetBuilder,
        FacebookBuilder $FacebookBuilder,
        HttpequivBuilder $HttpequivBuilder,
        StandardBuilder $StandardBuilder,
        TwitterBuilder $TwitterBuilder,
        ItempropBuilder $ItempropBuilder
    ) {

        // 設定參數備忘
        if (empty($this->Config)) {
            $this->Config = collect(config('meta.BuilderKeysMap'));
        }

        $this->Classify = $Classify;

        // 初始化 設定 Config
        $this->Classify->init($this->Config);

        // 所有的建造者
        $this->Builder = collect([
            'Charset'   => $CharsetBuilder,
            'Facebook'  => $FacebookBuilder,
            'Httpequiv' => $HttpequivBuilder,
            'Standard'  => $StandardBuilder,
            'Twitter'   => $TwitterBuilder,
            'Itemprop'  => $ItempropBuilder,
        ]);
    }

    /**
     * [init 設定初始參數]
     *
     * @Author    Boday
     * @DateTime  2017-09-04T11:46:45+0800
     * @return    [type]                    [description]
     */
    public function init(string $RouteName = null)
    {


        $is_init_finish = (isset($this->init_finish[$RouteName]) && $this->init_finish[$RouteName] == true);
        if ($is_init_finish) {
            return $this;
        }

        $service_domain_flag = get_service_domain_flag();
        /**
         * 如果發現 沒有 service domain flag
         */
        if (empty($service_domain_flag)) {
            throwException(MetaException::SERVICE_DOMAIN_FLAG_ERROR);
            return;
        }

        /**
         * 如果發現 沒有 metadata
         */
        if (Config::has('metadata') == false) {
            throwException(MetaException::METADATA_CONFIG_ERROR);
            return;
        }

        $config_service_domain_flag = str_replace('{service_domain_flag}', $service_domain_flag,
            'metadata.{service_domain_flag}');
        if (Config::has($config_service_domain_flag) == false) {
            throwException(MetaException::METADATA_ERROR);
            return;
        }

        $config_service_domain_flag_metas = str_replace('{service_domain_flag}', $service_domain_flag,
            'metadata.{service_domain_flag}.metas');
        if (Config::has($config_service_domain_flag_metas) == false) {
            throwException(MetaException::METADATA_METAS_ERROR);
            return;
        }

        $this->DefaultSteup = collect(config($config_service_domain_flag_metas));

        // 賦予預設值
        $this->DefaultSteup->collapse()->map(function ($item, $key) {
            $this->set($key, $item);
        });

        $this->init_finish[$RouteName] = true;

        return $this;
    }

    /**
     * [set description]
     *
     * @Author    Boday
     * @DateTime  2017-08-30T10:46:29+0800
     *
     * @param     [type]                    $MainAttributeValue       [description]
     * @param     [type]                    $SecondaryAttributeValue  [description]
     * @param     [type]                    $Arguments                [description]
     */
    public function set($MainAttributeValue, $SecondaryAttributeValue, $Arguments = null)
    {
        $FilterMapBag = $this->Classify->getRealKey($MainAttributeValue);

        $FilterMapBag->map(function ($Rows) use ($MainAttributeValue, $SecondaryAttributeValue, $Arguments) {
            $this->director($Rows['BuilderName'])->set($Rows['MainAttributeValue'], $SecondaryAttributeValue,
                $Arguments);
        });

        return $this;
    }

    /**
     * [director description]
     *
     * @Author    Boday
     * @DateTime  2017-08-30T09:00:29+0800
     *
     * @param     [type]                    $Builder  [description]
     *
     * @return    [type]                              [description]
     */
    public function director($Builder)
    {
        return $this->Builder->get($Builder);
    }

    /**
     * [get description]
     *
     * @Author    Boday
     * @DateTime  2017-08-30T10:37:08+0800
     *
     * @param     string $MainAttributeValue [description]
     *
     * @return    [type]                              [description]
     */
    public function get(string $MainAttributeValue)
    {
        $FilterMapBag = $this->Classify->getRealKey($MainAttributeValue);

        if (empty($FilterMapBag)) {
            return;
        }

        $Result = $FilterMapBag->map(function ($Rows, $BuilderName) use ($MainAttributeValue) {
            $Result = $this->director($Rows['BuilderName'])->get($Rows['MainAttributeValue']);
            if (isset($Result)) {
                return $Result;
            }

        })->filter()->toarray();

        if (empty($Result)) {
            return;
        }

        return $Result;
    }

    /**
     * [all description]
     *
     * @Author    Boday
     * @DateTime  2017-08-30T11:26:16+0800
     * @return    [type]                    [description]
     */
    public function all(string $MainAttributeValue = null)
    {
        if (empty($MainAttributeValue)) {
            return $this->Builder->map(function ($item, $key) {
                return $item->all();
            })->filter();
        }

        // 自訂要查詢的 條件
        $FilterMapBag = $this->Classify->getRealKey($MainAttributeValue);
        return $FilterMapBag->map(function ($Rows, $BuilderName) use ($MainAttributeValue) {

            $Result = $this->director($Rows['BuilderName'])->all();
            if (isset($Result)) {
                return $Result;
            }
        })->filter();
    }

    /**
     * [html description]
     *
     * @Author    Boday
     * @DateTime  2017-08-31T14:50:21+0800
     * @return    [type]                    [description]
     */
    public function html()
    {
        if (empty($MainAttributeValue)) {

            // 先取得所有建造者的 HTML
            $Result = $this->Builder->map(function ($item, $key) {
                return $item->html();
            })->filter();

            // 進行組裝與排序輸出
            $HtmlList = collect(
                $this->Config->map(function ($Item, $Builder) use ($Result) {
                    if (empty($Result->get($Builder))) {
                        return;
                    }

                    $Attribute = $Result->get($Builder);
                    return array_reduce($Item, function ($Carry, $SubItem) use ($Attribute) {
                        if ($Attribute->get($SubItem)) {
                            $Carry[] = $Attribute->get($SubItem);
                        }
                        return $Carry;
                    });

                })
            )->filter()->collapse()->all();
            if (empty($HtmlList)) {
                return;
            }
            return implode(PHP_EOL, $HtmlList);
        }
    }

}
