<?php

/**
 * IDE Helper for Livewire Components
 * This file helps IDE understand Livewire magic methods and properties
 */

namespace {
    // Global helper functions
    if (!function_exists('view')) {
        function view($view, $data = [], $mergeData = []) {
            return app('view')->make($view, $data, $mergeData);
        }
    }
    
    if (!function_exists('route')) {
        function route($name, $parameters = [], $absolute = true) {
            return app('url')->route($name, $parameters, $absolute);
        }
    }
}

namespace Livewire {
    /**
     * @method void emit(string $event, ...$params)
     * @method void emitTo(string $name, string $event, ...$params)
     * @method void emitUp(string $event, ...$params)
     * @method void dispatchBrowserEvent(string $event, array $data = [])
     * @method array validate()
     * @method array validateOnly(string $field)
     * @method void reset()
     * @method void reset(string|array $fields)
     */
    abstract class Component
    {
        public function render() {}
        public function mount() {}
        public function hydrate() {}
        public function dehydrate() {}
    }
}

namespace Illuminate\Database\Eloquent {
    /**
     * @method static Builder|Model with($relations)
     * @method static Builder|Model where($column, $operator = null, $value = null, $boolean = 'and')
     * @method static Builder|Model orderBy($column, $direction = 'asc')
     * @method static Builder|Model paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
     */
    class Model {}
}

namespace Illuminate\Support\Facades {
    /**
     * @method static mixed get($key, $default = null)
     * @method static void put($key, $value, $ttl = null)
     * @method static bool has($key)
     * @method static void forget($key)
     */
    class Cache {}
    
    /**
     * @method static string getId()
     */
    class Session {}
    
    /**
     * @method static bool environment($environment)
     */
    class App {}
    
    /**
     * @method static string url($path = null, array $parameters = [], $secure = null)
     */
    class URL {}
    
    /**
     * @method static mixed get($array, $key, $default = null)
     * @method static array except($array, $keys)
     * @method static array only($array, $keys)
     */
    class Arr {}
}

namespace Illuminate\Support {
    /**
     * @property-read int $timestamp
     * @method static Carbon now()
     * @method static Carbon today()
     * @method static Carbon tomorrow()
     * @method static Carbon yesterday()
     * @method Carbon subMinutes(int $value)
     */
    class Carbon {}
}

namespace Illuminate\Support\Facades {
    /**
     * @method static mixed zadd($key, ...$scores)
     * @method static mixed zrem($key, ...$members)
     * @method static mixed zcard($key)
     * @method static mixed zremrangebyscore($key, $min, $max)
     * @method static mixed expire($key, $seconds)
     */
    class Redis {}
}
