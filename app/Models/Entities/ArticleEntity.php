<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Concerns\Commons\Traits\StatusActiveEntityTrait;
use App\Scopes\RootStatus;

/**
 * App\Models\Entities\ArticleEntity
 *
 * @property int $id
 * @property int|null $user_id user 的流水號
 * @property string|null $title 標題
 * @property string|null $content 內容
 * @property array|mixed $images 圖片位置(序列化)
 * @property array|mixed $seo seo(序列化)
 * @property int $status 狀態
 * @property int|null $created_by 建立者ID
 * @property int|null $updated_by 修改者ID
 * @property int|null $deleted_by 刪除者ID
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Entities\CommentEntity[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Entities\UserEntity|null $users
 * @method static \Database\Factories\Entities\ArticleEntityFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleEntity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleEntity newQuery()
 * @method static \Illuminate\Database\Query\Builder|ArticleEntity onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleEntity query()
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleEntity whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleEntity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleEntity whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleEntity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleEntity whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleEntity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleEntity whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleEntity whereSeo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleEntity whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleEntity whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleEntity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleEntity whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ArticleEntity whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|ArticleEntity withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ArticleEntity withoutTrashed()
 * @mixin \Eloquent
 */
class ArticleEntity extends Model
{
    use HasFactory;
    use SoftDeletes;

    const Table = 'articles';
    /**
     * @var string
     * @Author  : daniel
     * @DateTime: 2020-05-13 16:28
     */

    /**
     * @var string
     * @Author  : daniel
     * @DateTime: 2020-05-13 16:28
     */
    protected $table = self::Table;
    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'status',
        'seo',
        'images',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
    ];
    /**
     * @var string[]
     */
    protected $hidden = [

    ];

    /**
     * @Author: Roy
     * @DateTime: 2021/9/3 上午 12:18
     */
    protected static function boot()
    {
        parent::boot();
    }
    /**
     * @Author: Roy
     * @DateTime: 2021/8/11 下午 03:04
     */
    public function users()
    {
        return $this->hasOne(UserEntity::class, 'id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @Author: Roy
     * @DateTime: 2021/8/11 下午 06:27
     */
    public function comments()
    {
        return $this->hasMany(CommentEntity::class, 'article_id', 'id');
    }

    /**
     * @param $value
     *
     * @return array|mixed
     * @Author: Roy
     * @DateTime: 2021/8/19 下午 09:13
     */
    public function getImagesAttribute($value)
    {
        return empty($value) ? [] : unserialize($value);
    }

    /**
     * @param  array  $value
     *
     * @Author: Roy
     * @DateTime: 2021/8/19 下午 09:13
     */
    public function setImagesAttribute(array $value)
    {
        $this->attributes['images'] = serialize($value);
    }
    /**
     * @param $value
     *
     * @return array|mixed
     * @Author: Roy
     * @DateTime: 2021/8/19 下午 09:13
     */
    public function getSeoAttribute($value)
    {
        return empty($value) ? [] : unserialize($value);
    }

    /**
     * @param  array  $value
     *
     * @Author: Roy
     * @DateTime: 2021/8/19 下午 09:13
     */
    public function setSeoAttribute(array $value)
    {
        $this->attributes['seo'] = serialize($value);
    }

    /**
     * @param $query
     *
     * @return mixed
     * @Author: Roy
     * @DateTime: 2021/9/3 上午 12:22
     */
    public function scopeWebArticle($query)
    {
        return $query
            ->where('user_id',32);
    }
}
