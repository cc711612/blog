<?php

namespace App\Helpers\UploadFiles\Entities;

use App\Concerns\Commons\Traits\StatusActiveEntityTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Helpers\UploadFiles\Entities\Image
 *
 * @property int $id 流水號
 * @property string $title 圖檔抬頭
 * @property string $url 圖檔唯一路徑
 * @property string $mime_type 圖檔唯格式
 * @property int $size 原始圖檔大小
 * @property string $filename 檔名
 * @property string $extension 副檔名
 * @property int $width 原始圖檔寬度
 * @property int $height 原始圖檔高度
 * @property int $status 圖檔上架狀態
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Helpers\UploadFiles\Entities\Image adminActivity()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Helpers\UploadFiles\Entities\Image draft()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Helpers\UploadFiles\Entities\Image mainActivity()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Helpers\UploadFiles\Entities\Image mainActivityWithDate()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Helpers\UploadFiles\Entities\Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Helpers\UploadFiles\Entities\Image newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Helpers\UploadFiles\Entities\Image onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Helpers\UploadFiles\Entities\Image query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Helpers\UploadFiles\Entities\Image rootStatus()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Helpers\UploadFiles\Entities\Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Helpers\UploadFiles\Entities\Image whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Helpers\UploadFiles\Entities\Image whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Helpers\UploadFiles\Entities\Image whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Helpers\UploadFiles\Entities\Image whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Helpers\UploadFiles\Entities\Image whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Helpers\UploadFiles\Entities\Image whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Helpers\UploadFiles\Entities\Image whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Helpers\UploadFiles\Entities\Image whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Helpers\UploadFiles\Entities\Image whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Helpers\UploadFiles\Entities\Image whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Helpers\UploadFiles\Entities\Image whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Helpers\UploadFiles\Entities\Image whereWidth($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Helpers\UploadFiles\Entities\Image withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Helpers\UploadFiles\Entities\Image withoutTrashed()
 * @mixin \Eloquent
 * @property null $end_at
 * @property null $start_at
 */
class Image extends Model
{
    use StatusActiveEntityTrait;

    const Table = 'images';
    /**
     * @var string
     * @Author  : daniel
     * @DateTime: 2020-05-13 16:28
     */
    protected $connection = 'default';

    /**
     * @var string
     * @Author  : daniel
     * @DateTime: 2020-05-13 16:28
     */
    protected $table = self::Table;

    // 開始軟刪除機制
    use SoftDeletes;

    // 可以透過程式寫入的欄位
    protected $fillable = [
        'id',
        'title',
        'url',
        'mime_type',
        'size',
        'filename',
        'extension',
        'width',
        'height',
        'status',
    ];

    protected $hidden = ['deleted_at', 'created_at'];

}
