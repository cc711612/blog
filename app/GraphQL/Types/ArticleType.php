<?php
/**
 * @Author: Roy
 * @DateTime: 2022/5/10 下午 11:36
 */

namespace App\GraphQL\Types;


use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;
use GraphQL\Type\Definition\Type;
use App\Models\Entities\ArticleEntity;
use App\Models\Entities\UserEntity;

class ArticleType extends GraphQLType
{

    protected $attributes = [
        'name'        => ArticleEntity::Table,
        'description' => 'Articles',
        'model'       => ArticleEntity::class,
    ];

    public function fields(): array
    {
        return [
            'id'      => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'ID of article',
            ],
            'title'   => [
                'type'        => Type::nonNull(Type::string()),
                'description' => 'title of article',
            ],
            'content' => [
                'type'        => Type::nonNull(Type::string()),
                'description' => 'content of article',
            ],
            'users'    => [
                'type'        => GraphQL::type(UserEntity::Table),
                'description' => 'user of article',
            ],
        ];
    }
}
