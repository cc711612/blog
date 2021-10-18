<?php

namespace Database\Factories\Entities;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Entities\CommentEntity;

class CommentEntityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CommentEntity::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user_id = rand(1, 30);
        $article_id = rand(1, 30);
        return [
            'user_id'    => $user_id,
            'article_id' => $article_id,
            'content'    => $this->faker->text('50'),
            'status'     => 1,
            'created_by' => $user_id,
            'updated_by' => $user_id,
        ];
    }
}
