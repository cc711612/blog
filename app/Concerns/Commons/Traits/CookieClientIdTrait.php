<?php

namespace App\Concerns\Commons\Traits;

/**
 * Trait CookieClientIdTrait
 *
 * @package App\Concerns\Commons\Traits
 * @Author  : daniel
 * @DateTime: 2020/7/14 9:47 上午
 */
trait CookieClientIdTrait
{
    /**
     * @var string 使用者的 Cookie Client ID
     */
    private $cookie_client_id;

    /**
     * @return string
     * @Author  : daniel
     * @DateTime: 2020/7/14 9:47 上午
     */
    public function getCookieClientId(): string
    {

        /**
         * 如果已經有設定過 $this->cookie_client_id
         */
        $is_has_cookie_client_id = (isset($this->cookie_client_id)
            && is_null($this->cookie_client_id) == false
            && strlen($this->cookie_client_id) > 0);

        if ($is_has_cookie_client_id) {
            return $this->cookie_client_id;
        }

        // 取得 Cookie Client Id
        $cookie_client_id        = app('App\Cookies\ClientIdCookie')->get();
        $is_has_cookie_client_id = (isset($cookie_client_id)
            && is_null($cookie_client_id) == false
            && strlen($cookie_client_id) > 0);

        if ($is_has_cookie_client_id) {
            $this->cookie_client_id = $cookie_client_id;
            return $this->cookie_client_id;
        }

        return '';
    }

}
