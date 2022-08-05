<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Entities\UserEntity;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class InitializationUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $insert_data = [
            [
                'name'              => '測試帳號',
                'email'             => 'root@root.com',
                'email_verified_at' => now(),
                'password'          => Hash::make('123456789'),
//                'remember_token'    => Str::random(10),
                'api_token'         => Str::random(10),
            ],
        ];

        Schema::disableForeignKeyConstraints();
        UserEntity::truncate();
        UserEntity::insert($insert_data);
        Schema::enableForeignKeyConstraints();

        echo self::class.' Complete'.PHP_EOL.PHP_EOL;

    }
}
