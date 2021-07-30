<?php

namespace App\Helpers\HttpUri\Caches;

use Illuminate\Support\Facades\Cache;

class HttpUriCache
{
    /**
     * [$cache_item_tag 設定 Cache Tag]
     * @var  string
     */
    private $cache_item_tag = 'Uri';
    private $cache_item_minutes = 86400;

    /**
     * [hasItem 檢查是否有設定過快取]
     * @Author    Boday
     * @DateTime  2017-08-15T09:19:52+0800
     * @param     string                    $key  [description]
     * @return    boolean                         [description]
     */
    public function hasItem(string $key)
    {
        return Cache::tags($this->cache_item_tag)->has($key);
    }

    /**
     * [getItem 取得快取資料]
     * @Author    Boday
     * @DateTime  2017-08-15T09:19:28+0800
     * @param     string                    $key  [description]
     * @return    [type]                          [description]
     */
    public function getItem(string $key)
    {
        // 檢查是否有 Cache 存在
        if (!$this->hasItem($key)) {
            return;
        }
        return Cache::tags($this->cache_item_tag)->get($key);
    }

    /**
     * [putItem 將資料寫入快取]
     * @Author    Boday
     * @DateTime  2017-08-15T09:22:20+0800
     * @param     string                    $key    [description]
     * @param     [type]                    $value  [description]
     * @return    [type]                            [description]
     */
    public function putItem(string $key, $value)
    {
        return Cache::tags($this->cache_item_tag)->put($key, $value, $this->cache_item_minutes);
    }

    /**
     * [deleteItem description]
     * @Author    Boday
     * @DateTime  2017-12-05T09:46:59+0800
     * @param     string                    $key  [description]
     * @return    [type]                          [description]
     */
    public function deleteItem(string $key)
    {
        return Cache::tags($this->cache_item_tag)->forget($key);
    }

    /**
     * [flush 清除所有的 System Cache ]
     * @Author    Boday
     * @DateTime  2017-08-15T09:18:07+0800
     * @return    [type]                    [description]
     */
    public function flush()
    {
        return Cache::tags($this->cache_item_tag)->flush();
    }
}
