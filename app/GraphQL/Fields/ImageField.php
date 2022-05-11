<?php
/**
 * @Author: Roy
 * @DateTime: 2022/5/11 下午 10:28
 */

namespace App\GraphQL\Fields;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Field;
use App\Traits\ImageTrait;
use Illuminate\Support\Arr;

class ImageField extends Field
{
    use ImageTrait;

    protected $attributes = [
        'description' => 'A image',
    ];

    public function type(): Type
    {
        return Type::string();
    }

    public function args(): array
    {
        return [
            'cover' => [
                'type'        => Type::string(),
                'description' => 'cover url',
            ],
        ];
    }

    protected function resolve($root, array $args)
    {
        return $this->handleUserImage(Arr::get($root, 'images.cover'));
    }
}
