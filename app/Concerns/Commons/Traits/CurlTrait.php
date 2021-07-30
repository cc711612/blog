<?php
namespace App\Concerns\Commons\Traits;

use Illuminate\Support\Facades\Log;

/**
 * Trait CurlTrait
 *
 * @package App\Concerns\Commons\Traits
 * @Author  : steatng
 * @DateTime: 2020/7/17 14:32
 */
trait CurlTrait
{
    /**
     * @var
     * @Author  : steatng
     * @DateTime: 2020/7/17 14:24
     */
    private $cURL;
    /**
     * @var
     * @Author  : steatng
     * @DateTime: 2020/7/17 14:24
     */
    private $header = [
        'Accept: application/json',
        'X-Requested-With: XMLHttpRequest'
    ];
    /**
     * @var
     * @Author  : steatng
     * @DateTime: 2020/7/17 14:24
     */
    private $post_params = [];
    /**
     * @var
     * @Author  : steatng
     * @DateTime: 2020/7/17 14:24
     */
    private $get_params;

    /**
     * @Author  : steatng
     * @DateTime: 2020/7/17 14:24
     */
    public function init()
    {
        $this->cURL = curl_init();
    }

    /**
     * @return mixed
     * @Author  : steatng
     * @DateTime: 2020/7/17 14:24
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @param string $header
     *
     * @return $this
     * @Author  : steatng
     * @DateTime: 2020/7/17 14:24
     */
    public function setHeader(string $header)
    {
        $this->header[] = $header;
        return $this;
    }

    /**
     * @return mixed
     * @Author  : steatng
     * @DateTime: 2020/7/17 14:24
     */
    public function getPostParams()
    {
        return $this->post_params;
    }

    /**
     * @param array $post_params
     *
     * @return $this
     * @Author  : steatng
     * @DateTime: 2020/7/17 14:24
     */
    public function setPostParams(array $post_params)
    {
        $this->post_params = array_merge($this->getPostParams(), $post_params);
        return $this;
    }

    /**
     * @return mixed
     * @Author  : steatng
     * @DateTime: 2020/7/17 14:24
     */
    public function getGetParams()
    {
        return $this->get_params;
    }

    /**
     * @param array $get_params
     *
     * @return $this
     * @Author  : steatng
     * @DateTime: 2020/7/17 14:24
     */
    public function setGetParams(array $get_params)
    {
        $this->get_params = $get_params;
        return $this;
    }

    /**
     * @param string $url
     *
     * @return array
     * @Author  : steatng
     * @DateTime: 2020/7/17 14:24
     */
    public function exec(string $url)
    {
        $this->init();
        curl_setopt($this->cURL, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->cURL, CURLOPT_HEADER, false);
        curl_setopt($this->cURL, CURLOPT_USERAGENT, \Request::header('User-Agent'));

        if(empty($this->getGetParams()) == false){
            $url = $this->handle_url($url);
        }

        curl_setopt($this->cURL, CURLOPT_URL, $url);


        curl_setopt($this->cURL, CURLOPT_VERBOSE, true);
        curl_setopt($this->cURL,  CURLOPT_RETURNTRANSFER, true);

        # 增加 Header
        if(empty($this->getHeader()) == false){
            curl_setopt($this->cURL, CURLOPT_HTTPHEADER, $this->getHeader());
        }

        if(empty($this->getPostParams()) == false){
            curl_setopt($this->cURL, CURLOPT_POST, true);
            curl_setopt($this->cURL, CURLOPT_POSTFIELDS, http_build_query($this->getPostParams()));
        }

        $output = curl_exec($this->cURL);

        $http_code = curl_getinfo($this->cURL, CURLINFO_HTTP_CODE);

        if($http_code !== 200){
            if(config('app.env') === 'dev'){
                dump(curl_error($this->cURL));
            }else{
                Log::critical(curl_error($this->cURL));
            }
        }

        $this->close();

        return json_decode($output, true);
    }

    private function close()
    {
        curl_close($this->cURL);
        $this->header = [
            'Accept: application/json',
            'X-Requested-With: XMLHttpRequest'
        ];
        $this->get_params = null;
        $this->post_params = [];
    }

    /**
     * @param array $parts
     *
     * @return string
     * @Author  : steatng
     * @DateTime: 2020/7/17 14:24
     */
    private function build_url(array $parts) {
        return (isset($parts['scheme']) ? "{$parts['scheme']}:" : '') .
            ((isset($parts['user']) || isset($parts['host'])) ? '//' : '') .
            (isset($parts['user']) ? "{$parts['user']}" : '') .
            (isset($parts['pass']) ? ":{$parts['pass']}" : '') .
            (isset($parts['user']) ? '@' : '') .
            (isset($parts['host']) ? "{$parts['host']}" : '') .
            (isset($parts['port']) ? ":{$parts['port']}" : '') .
            (isset($parts['path']) ? "{$parts['path']}" : '') .
            (isset($parts['query']) ? "?{$parts['query']}" : '') .
            (isset($parts['fragment']) ? "#{$parts['fragment']}" : '');
    }

    /**
     * @param string $url
     *
     * @return string
     * @Author  : steatng
     * @DateTime: 2020/8/7 16:43
     */
    private function handle_url(string $url)
    {
        $replace_value = [];
        $query_value = [];

        # 分類 key 如果是{}則需要取代 url
        foreach($this->getGetParams() as $key => $value){
            if( preg_match('/{.*}/m', $key) ){
                $replace_value[$key] = $value;
            }else{
                $query_value[$key] = $value;
            }
        }

        $url = str_replace(
            array_keys($replace_value),
            array_values($replace_value),
            $url
        );

        if(empty($query_value) == false){
            # 解析 url
            $urlarr = parse_url($url);
            parse_str($urlarr['query'] ?? "", $query_arr);
            $urlarr['query'] = http_build_query(array_merge($query_arr, $query_value));
            $url = $this->build_url($urlarr);
        }

        return $url;
    }
}
