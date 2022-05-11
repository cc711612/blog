<?php
/**
 * @Author: Roy
 * @DateTime: 2022/5/10 下午 11:45
 */

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use App\GraphQL\Middleware\ResolvePage;
use Illuminate\Support\Arr;
use App\GraphQL\Middleware\Logstash;
use App\Models\Entities\CommentEntity;

class CommentQuery extends Query
{
    /**
     * @var string[]
     */
    protected $middleware = [
        ResolvePage::class,
        Logstash::class,
    ];
    /**
     * @var array
     */
    protected $attributes = [
        'name' => CommentEntity::Table,
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type(CommentEntity::Table));
    }

    /**
     * 接收引數的型別定義
     *
     * @return array
     */
    public function args(): array
    {
        return [
            'id'      => ['name' => 'id', 'type' => Type::int()],
            'limit'   => ['name' => 'limit', 'type' => Type::int()],
            'page'    => ['name' => 'page', 'type' => Type::int()],
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

        $Entity = new CommentEntity();

        if (isset($args['limit'])) {
            $Entity = $Entity->limit($args['limit']);
        }

        if (isset($args['id'])) {
            $Entity = $Entity->where('id', $args['id']);
        }

        return $Entity->paginate($args['limit'], ['*'], 'page', $args['page']);
    }
}
