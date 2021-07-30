<?php

namespace App\Helpers\HttpUri\Services;

use App\Helpers\HttpUri\Caches\HttpUriCache;
use App\Helpers\HttpUri\Exceptions\HttpUriException;
use Illuminate\Support\Facades\Storage;

/**
 *
 */
class HttpUriService
{

    /**
     * [$HttpUriCache description]
     * @var  [type]
     */
    protected $HttpUriCache;

    /**
     * [$ResultBag 重 DB or Cache 取得的結果集]
     * @var  [type]
     */
    private $ResultBag;

    private $storage_disk = 'http_public';

    /**
     * [__construct description]
     * @Author    Boday
     * @DateTime  2017-12-04T16:14:24+0800
     * @param     HttpUriCache              $HttpUriCache  [description]
     */
    public function __construct(HttpUriCache $HttpUriCache)
    {
        $this->HttpUriCache = $HttpUriCache;
    }

    /**
     * [getItem description]
     * @Author    Boday
     * @DateTime  2017-12-04T16:45:51+0800
     * @param     string                    $value  [description]
     * @return    [type]                            [description]
     */
    public function getItem(string $value)
    {

        // 檢查 Cache 是否存在
        $varsion = $this->HttpUriCache->getItem($value);

        if (isset($varsion)) {
            return $varsion;
        }

        // 如果檔案不存在
        if (empty(Storage::disk($this->storage_disk)->exists($value))) {
            throwException(HttpUriException::FILE_DOES_NOT_EXIST, $value);
            return;
        }

        $varsion = Storage::disk($this->storage_disk)->lastModified($value);

        // 回寫 Cache
        $this->HttpUriCache->putItem($value, $varsion);

        return $varsion;
    }

    /**
     * [reflashItem description]
     * @Author    Boday
     * @DateTime  2017-12-05T09:45:45+0800
     * @param     [type]                    $value  [description]
     * @return    [type]                            [description]
     */
    public function reflashItem($value)
    {
        $this->HttpUriCache->deleteItem($value);
    }

    /**
     * [flush description]
     * @Author    Boday
     * @DateTime  2017-10-17T14:14:04+0800
     * @return    [type]                    [description]
     */
    public function flush()
    {
        return $this->HttpUriCache->flush();
    }

}
