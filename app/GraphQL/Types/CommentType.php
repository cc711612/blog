<?php
/**
 * @Author: Roy
 * @DateTime: 2022/5/10 下午 11:36
 */

namespace App\GraphQL\Types;

use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;
use App\Models\Entities\UserEntity;
use App\Models\Entities\CommentEntity;
use App\Models\Entities\ArticleEntity;

class CommentType extends GraphQLType
{

    protected $attributes = [
        'name'        => CommentEntity::Table,
        'description' => 'Comments',
        'model'       => CommentEntity::class,
    ];

    public function fields(): array
    {
        return [
            'id'              => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'ID of comment',
            ],
            'content'         => [
                'type'        => Type::nonNull(Type::string()),
                'description' => 'content of comment',
            ],
            UserEntity::Table    => [
                'type'        => GraphQL::type(UserEntity::Table),
                'description' => 'user of comment',
            ],
        ];
    }
}
