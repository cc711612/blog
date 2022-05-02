<?php
/**
 * @Author: Roy
 * @DateTime: 2021/8/12 下午 09:04
 */

namespace App\Traits;


use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Cache;
use Illuminate\Support\Facades\Log;

trait ImageTrait
{
    /**
     * @return string
     * @Author: Roy
     * @DateTime: 2022/5/1 上午 11:48
     */
    public function getDefaultImage(): string
    {
        return sprintf('%s://%s%s%s', $_SERVER['REQUEST_SCHEME'], $_SERVER["HTTP_HOST"],
            config('filesystems.disks.images.url'), 'default.png');
    }

    /**
     * @param  string|null  $url
     *
     * @return string
     * @Author: Roy
     * @DateTime: 2022/5/1 上午 11:48
     */
    public function handleUserImage(string $url = null)
    {
        if (is_null($url) === false) {
            if (Str::contains($url, 'http') === false) {
                return $this->getAdminImageUrl($url);
            }
            return $url;
        }
        return $this->getDefaultImage();
    }

    /**
     * @param  string  $url
     *
     * @return string
     * @Author: Roy
     * @DateTime: 2022/5/1 上午 11:48
     */
    public function getAdminImageUrl(string $url): string
    {
        return sprintf("%s%s", config('filesystems.disks.admin.url'), $url);
    }
}

