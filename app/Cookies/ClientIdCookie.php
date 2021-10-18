<?php

namespace App\Cookies;

use App\Concerns\Commons\Abstracts\CookieAbstracts;
use Str;

/**
 * Class ClientIdCookie
 *
 * @package App\Cookies
 * @Author  : daniel
 * @DateTime: 2020/7/14 9:40 上午
 */
class ClientIdCookie extends CookieAbstracts
{

    // 設定 ECStore set Cookie Client Id
    const COOKIE_NAME = 'BROWSER-CLIENT-ID';

    /**
     * @var int 如果設定值小於 1 將會是永久的 Cookie
     */
    protected $expired_minutes = 0;

    /**
     * @return mixed|string
     * @throws \Exception
     * @Author  : daniel
     * @DateTime: 2020/7/14 9:40 上午
     */
    public function genValue()
    {
        return Str::Uuid();
    }

}
