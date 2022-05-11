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
use App\Models\Entities\ArticleEntity;
use App\GraphQL\Fields\ImageField;

class UserType extends GraphQLType
{
    protected $attributes = [
        'name'        => UserEntity::Table,
        'description' => 'Users',
        'model'       => UserEntity::class,
    ];

    public function fields(): array
    {
        return [
            'id'           => [
                'type'        => Type::nonNull(Type::int()),
                'description' => 'id of user',
            ],
            'name'         => [
                'type'        => Type::nonNull(Type::string()),
                'description' => 'name of user',
            ],
            'email'        => [
                'type'        => Type::nonNull(Type::string()),
                'description' => 'email of user',
            ],
            'introduction' => [
                'type'        => Type::string(),
                'description' => 'introduction of user',
            ],
            'images'       => ImageField::class,
            'articles'     => [
                'type'        => Type::listOf(GraphQL::type(ArticleEntity::Table)),
                'description' => 'articles of user',
            ],
        ];
    }
}
