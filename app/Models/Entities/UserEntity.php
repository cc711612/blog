<?php

namespace App\Models\Entities;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Entities\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $images 圖片位置(序列化)
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $api_token
 * @property int|null $created_by 建立者ID
 * @property int|null $updated_by 修改者ID
 * @property int|null $deleted_by 刪除者ID
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Entities\ArticleEntity[] $articles
 * @property-read int|null $articles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Entities\CommentEntity[] $comments
 * @property-read int|null $comments_count
 * @method static \Illuminate\Database\Query\Builder|UserEntity onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity whereApiToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity whereDeletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserEntity whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|UserEntity withTrashed()
 * @method static \Illuminate\Database\Query\Builder|UserEntity withoutTrashed()
 */
class UserEntity extends Authenticatable
{
    use SoftDeletes;
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    const Table = 'users';
    /**
     * @var string
     * @Author  : daniel
     * @DateTime: 2020-05-13 16:28
     */
//    protected $connection = 'default';

    /**
     * @var string
     * @Author  : daniel
     * @DateTime: 2020-05-13 16:28
     */
    protected $table = self::Table;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'images',
        'created_by',
        'updated_by',
        'deleted_by',
        'api_token ',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
//        'profile_photo_url',
    ];

    /**
     * @param $value
     *
     * @return array|mixed
     * @Author: Roy
     * @DateTime: 2021/8/7 下午 02:37
     */
    public function getImagesAttribute($value)
    {
        return empty($value) ? [] : unserialize($value);
    }

    /**
     * @param  array  $value
     *
     * @Author: Roy
     * @DateTime: 2021/8/7 下午 02:37
     */
    public function setImagesAttribute(array $value)
    {
        $this->attributes['images'] = serialize($value);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @Author: Roy
     * @DateTime: 2021/8/11 下午 03:08
     */
    public function articles()
    {
        return $this->hasMany(ArticleEntity::class,  'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @Author: Roy
     * @DateTime: 2021/8/11 下午 06:50
     */
    public function comments()
    {
        return $this->hasMany(CommentEntity::class,  'user_id', 'id');
    }
}
