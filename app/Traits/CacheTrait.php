<?php
/**
 * @Author: Roy
 * @DateTime: 2021/8/12 下午 09:04
 */

namespace App\Traits;
use Illuminate\Support\Facades\Cache;

trait CacheTrait
{
    /**
     * @param  int  $page
     *
     * @return string
     * @Author: Roy
     * @DateTime: 2022/8/5 下午 09:34
     */
    private function getArticleIndexCacheKey(int $page = 1)
    {
        return sprintf('article.list.%s', $page);
    }

    /**
     * @param  int  $page
     *
     * @return $this
     * @Author: Roy
     * @DateTime: 2022/8/5 下午 09:36
     */
    private function forgetArticleIndexCache(int $page = 1)
    {
        $cache_key = $this->getArticleIndexCacheKey($page);
        if (Cache::has($cache_key) === true) {
            Cache::forget($cache_key);
        }
        return $this;
    }
}

