<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Entities\CommentEntity
 *
 * @property int $id
 * @property int|null $user_id user 的流水號
 * @property int|null $article_id article 的流水號
 * @property string|null $content 內容
 * @property int $status 狀態
 * @property int|null $created_by 建立者ID
 * @property int|null $updated_by 修改者ID
 * @property int|null $deleted_by 刪除者ID
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Entities\ArticleEntity|null $articles
 * @property-read \App\Models\Entities\UserEntity|null $users
 * @method static \Database\Factories\Entities\CommentEntityFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentEntity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommentEntity newQuery()
 * @method static \Illuminate\Database\Query\Builder|CommentEntity onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CommentEntity query()
 * @method static \Illuminate\Database\Eloquent\Builder|CommentEntity whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentEntity whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentEntity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentEntity whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentEntity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentEntity whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentEntity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentEntity whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentEntity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentEntity whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentEntity whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|CommentEntity withTrashed()
 * @method static \Illuminate\Database\Query\Builder|CommentEntity withoutTrashed()
 * @mixin \Eloquent
 */
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
