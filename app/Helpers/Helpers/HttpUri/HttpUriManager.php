<?php
namespace App\Helpers\HttpUri;

use App\Helpers\HttpUri\Exceptions\HttpUriException;
use App\Helpers\HttpUri\Services\HttpUriService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

class HttpUriManager
{
    // Obj 我都用大駝峰
    protected $HttpUriService;

    // 一般變數 用蛇型
    private $uri_format = '%s/%s?%s=%s';

    private $secure;
    private $domain_name;
    private $url;
    private $version_key_name = 'v';
    private $version;

    private $reflash;

    /**
     * @var array
     * @Author  : boday
     * @DateTime: 2018/9/5 14:28
     */
    private $init_finish = [];

    public function __construct(HttpUriService $HttpUriService)
    {
        $this->HttpUriService = $HttpUriService;
    }

    /**
     * [init description]
     * @Author    Boday
     * @DateTime  2017-12-21T09:11:51+0800
     * @param     string|null               $RouteName  [description]
     * @return    [type]                                [description]
     */
    public function init(string $RouteName = null)
    {

        $is_init_finish = (isset($this->init_finish[$RouteName]) && $this->init_finish[$RouteName] == true);
        if ($is_init_finish) {
            return $this;
        }

        $service_domain_flag = get_service_domain_flag();

        // 檢查是否有設定檔
        if (Config::has('http_uri') == false) {
            throwException(HttpUriException::CONFIG_EROOR);
            return;
        }

        $config_service_domain_flag_domain_name = str_replace('{service_domain_flag}', $service_domain_flag, 'http_uri.{service_domain_flag}.domain_name');
        if (Config::has($config_service_domain_flag_domain_name) == false) {
            throwException(HttpUriException::CONFIG_DOMAIN_NAME_EROOR);
            return;
        }

        if (Config::has('filesystems.disks.http_public') == false) {
            throwException(HttpUriException::FILESYSTEMS_CONFIG_DISK_EROOR);
            return;
        }

        // 寫入設定檔設定值
        $this->setDomainName(config($config_service_domain_flag_domain_name));

        $config_service_domain_flag_secure = str_replace('{service_domain_flag}', $service_domain_flag, 'http_uri.{service_domain_flag}.secure');
        $this->setSecure(config($config_service_domain_flag_secure));

        $this->init_finish[$RouteName] = true;

        return $this;
    }

    /**
     * [setDomainName description]
     * @Author    Boday
     * @DateTime  2017-12-04T15:55:35+0800
     * @param     string|null                  $domain_name  [description]
     */
    public function setDomainName(string $domain_name = null)
    {

        if (isset($domain_name)) {
            $this->domain_name = $domain_name;
        }
        return $this;
    }

    /**
     * [setSecure description]
     * @Author    Boday
     * @DateTime  2017-12-04T16:18:41+0800
     * @param     bool|boolean              $secure  [description]
     */
    public function setSecure(bool $secure = true)
    {

        if (Request::secure()) {
            $this->secure = true;
            return $this;
        }

        $this->secure = $secure;
        return $this;
    }

    /**
     * [genUri description]
     * @Author    Boday
     * @DateTime  2017-12-04T17:01:03+0800
     * @return    [type]                    [description]
     */
    public function genUri()
    {
        return empty($this->domain_name) ? $this->getUrl() : $this->getSecureUri();
    }

    /**
     * [getSecureUri description]
     * @Author    Boday
     * @DateTime  2018-05-04T11:38:34+0800
     * @return    [type]                    [description]
     */
    private function getSecureUri()
    {
        return ($this->secure ? 'https://' : 'http://') . remove_many_slash(
            sprintf($this->uri_format,
                $this->domain_name,
                $this->url,
                $this->version_key_name,
                $this->version
            )
        );
    }

    private function getUrl()
    {
        return remove_many_slash(
            sprintf($this->uri_format,
                $this->domain_name,
                $this->url,
                $this->version_key_name,
                $this->version
            )
        );
    }

    /**
     * [reflash description]
     * @Author    Boday
     * @DateTime  2017-12-04T17:22:09+0800
     * @return    [type]                    [description]
     */
    public function reflash()
    {
        $this->reflash = true;
        return $this;
    }

    /**
     * [get description]
     * @Author    Boday
     * @DateTime  2017-12-04T17:06:29+0800
     * @param     [type]                    $url  [description]
     * @return    [type]                          [description]
     */
    public function get($url)
    {
        $this->url = $url;
        if ($this->reflash) {
            $this->HttpUriService->reflashItem($url);
        }
        $this->version = $this->HttpUriService->getItem($this->url);
        return $this->genUri();
    }

    /**
     * [reflashAll description]
     * @Author    Boday
     * @DateTime  2017-12-04T16:22:44+0800
     * @return    [type]                    [description]
     */
    public function reflashAll()
    {
        return $this->HttpUriService->flush();
    }

}
