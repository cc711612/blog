<?php

namespace App\Providers;

use App\Helpers\Exception\ExceptionRegister;
use App\Helpers\Htmls\HtmlRegister;
use App\Helpers\Metas\MetaRegister;
use App\Helpers\System\SystemRegister;
use App\Helpers\Trail\TrailRegister;
use App\Helpers\UploadFiles\UploadFileRegister;
use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{

    /**
     * [$BootFileNames 自訂函數檔案名稱
     * ]
     * @var  [type]
     */
    protected $BootFileNames = [
        'Base',
        // 全站共用常數表
//        'Constant',
        // 自建的 ServiceProvider 相關
        'ServiceProvider',
        // 物件相關擴充
        'Object',
    ];

    /**
     * [boot description]
     * @Author    Boday
     * @DateTime  2017-08-10T16:52:07+0800
     * @return    [type]                    [description]
     */
    public function boot()
    {
        /**
         * 引入所有的 Boots 檔案
         */
        foreach ($this->BootFileNames as $BootFileName) {

            $BootFilePath = app_path() . '/Helpers/Boots/' . $BootFileName . 'Boot.php';
            if (\File::isFile($BootFilePath)) {
                require_once $BootFilePath;
            }
        }
    }

    /**
     * [register description]
     * @Author    Boday
     * @DateTime  2017-08-10T20:04:52+0800
     * @return    [type]                    [description]
     */
    public function register()
    {
        // Exception
        $this->app->singleton('ExceptionRegister', function ($app) {
            return new ExceptionRegister();
        });

        // 系統環境參數
        $this->app->singleton('SystemRegister', function ($app) {
            return new SystemRegister(
                $app->make('App\Helpers\System\Services\SystemService')
            );
        });

        // Meta 生成器
        $this->app->singleton('MetaRegister', function ($app) {
            return new MetaRegister(
                $app->make('App\Helpers\Metas\MetaManager')
            );
        });

        // Trail 工廠
        $this->app->singleton('TrailRegister', function ($app) {
            return new TrailRegister(
                $app->make('App\Helpers\Trail\TrailManager')
            );
        });

        // Html 相關( JS & CSS )生成器
        $this->app->singleton('HtmlRegister', function ($app) {
            return new HtmlRegister(
                $app->make('App\Helpers\Htmls\HtmlManager')
            );
        });

        // 檔案上傳相關
        $this->app->singleton('UploadFileRegister', function ($app) {
            return new UploadFileRegister(
                $app->make('App\Helpers\UploadFiles\UploadFileManager')
            );
        });

        // HTTP URI Version --> 轉成新版本 App\
        /*$this->app->singleton('HttpUriRegister', function ($app) {
    return new HttpUriRegister(
    $app->make('App\Helpers\HttpUriGeneratorFacade\GeneratorManager')
    );
    });*/

    }
}
