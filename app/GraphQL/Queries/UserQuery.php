<?php
/**
 * @Author: Roy
 * @DateTime: 2022/5/10 下午 11:45
 */

namespace App\GraphQL\Queries;

use App\Models\Entities\UserEntity;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use App\GraphQL\Middleware\ResolvePage;

class UserQuery extends Query
{
//    protected $middleware = [
//        ResolvePage::class,
//    ];

    protected $attributes = [
        'name' => UserEntity::Table,
    ];

    public function type(): Type
    {
//        return GraphQL::paginate(UserEntity::Table);
        return Type::listOf(GraphQL::type(UserEntity::Table));
    }

    /**
     * 接收引數的型別定義
     *
     * @return array
     */
    public function args(): array
    {
        return [
            'id'    => ['name' => 'id', 'type' => Type::int()],
            'email' => ['name' => 'email', 'type' => Type::string()],
            'limit' => ['name' => 'limit', 'type' => Type::int()],
        ];
    }

    /**
     * @param $root
     * @param $args 傳入引數
     *
     * 處理請求的邏輯
     *
     * @return mixed
     */
    public function resolve($root, $args)
    {
        $Entity = new UserEntity();

        if(isset($args['limit']) ) {
            $Entity =  $Entity->limit($args['limit']);
        }

        if(isset($args['id']))
        {
            $Entity = $Entity->where('id' , $args['id']);
        }

        if(isset($args['email']))
        {
            $Entity = $Entity->where('email', $args['email']);
        }
        return $Entity->get();
    }
}
