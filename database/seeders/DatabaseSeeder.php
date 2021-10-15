<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Entities\ArticleEntity;
use Database\Factories\Entities\CommentEntityFactory;
use App\Models\Entities\CommentEntity;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//         \App\Models\UserEntity::factory(10)->create();
//         ArticleEntity::factory(30)->create();
        CommentEntity::factory(100)->create();
    }
}
