<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
