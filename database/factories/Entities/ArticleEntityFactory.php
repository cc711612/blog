<?php

namespace Database\Factories\Entities;

use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleEntityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Entities\ArticleEntity::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user_id = rand(1, 10);
        return [
            'user_id'    => $user_id,
            'title'      => $this->faker->text('20'),
            'content'    => $this->faker->paragraph(),
            'status'     => 1,
            'created_by' => $user_id,
            'updated_by' => $user_id,
        ];
    }
}
