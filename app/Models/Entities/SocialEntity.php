<?php

namespace App\Models\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class SocialEntity
 *
 * @package App\Models\Entities
 * @Author: Roy
 * @DateTime: 2021/8/20 下午 02:39
 */
class SocialEntity extends Model
{
    use SoftDeletes;

    const Table = 'socials';
    /**
     * @var string
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
        'social_type',
        'social_type_value',
        'image',
        'token',
        'followed',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function users()
    {
        return $this->belongsToMany(UserEntity::class, 'user_social', 'social_id', 'user_id');
    }
}
