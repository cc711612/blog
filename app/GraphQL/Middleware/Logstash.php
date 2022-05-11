<?php

namespace App\GraphQL\Middleware;

use Countable;
use GraphQL\Language\Printer;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Rebing\GraphQL\Support\Middleware;

class Logstash extends Middleware
{
    public function terminate($root, array $args, $context, ResolveInfo $info, $result): void
    {
        Log::channel('logstash')->info('', (
        collect([
            'query'     => $info->fieldName,
            'operation' => $info->operation->name->value ?? null,
            'type'      => $info->operation->operation,
            'fields'    => array_keys(Arr::dot($info->getFieldSelection($depth = PHP_INT_MAX))),
            'schema'    => Arr::first(Route::current()->parameters()) ?? Config::get('graphql.default_schema',
                    'default'),
            'vars'      => $this->formatVariableDefinitions($info->operation->variableDefinitions),
        ])
            ->when($result instanceof Countable, function ($metadata) use ($result) {
                return $metadata->put('count', $result->count());
            })
            ->when($result instanceof AbstractPaginator, function ($metadata) use ($result) {
                return $metadata->put('per_page', $result->perPage());
            })
            ->when($result instanceof LengthAwarePaginator, function ($metadata) use ($result) {
                return $metadata->put('total', $result->total());
            })
            ->merge($this->formatArguments($args))
            ->toArray()
        ));
    }

    private function formatArguments(array $args): array
    {
        return collect(Arr::sanitize($args))
            ->mapWithKeys(function ($value, $key) {
                return ["\${$key}" => $value];
            })
            ->toArray();
    }

    private function formatVariableDefinitions(?iterable $variableDefinitions = []): array
    {
        return collect($variableDefinitions)
            ->map(function ($def) {
                return Printer::doPrint($def);
            })
            ->toArray();
    }
}
