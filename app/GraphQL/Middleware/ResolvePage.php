<?php

declare(strict_types=1);

namespace App\GraphQL\Middleware;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\Middleware;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;

class ResolvePage extends Middleware
{
    public function handle($root, array $args, $context, ResolveInfo $info, Closure $next)
    {
        Paginator::currentPageResolver(function () use ($args) {
            return $args['pagination']['page'] ?? 1;
        });
        if (is_null(Arr::get($args, 'limit'))) {
            Arr::set($args, 'limit', 30);
        }
        if (is_null(Arr::get($args, 'page'))) {
            Arr::set($args, 'page', 1);
        }
        return $next($root, $args, $context, $info);
    }
}
