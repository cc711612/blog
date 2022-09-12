<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Entities\ArticleEntity;
use App\Models\Entities\UserEntity;
use App\Models\Entities\CommentEntity;

class TestDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        UserEntity::factory(30)->create();
        ArticleEntity::factory(30)->create();
        CommentEntity::factory(100)->create();
    }
}
