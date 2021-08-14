<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommentEntity extends Model
{
    use HasFactory;
    use SoftDeletes;

    const Table = 'comments';
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
        'article_id',
        'content',
        'status',
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
     * @DateTime: 2021/8/11 下午 06:31
     */
    public function articles()
    {
        return $this->hasOne(ArticleEntity::class, 'id', 'article_id');
    }
}
