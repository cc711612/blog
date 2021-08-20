<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InitializationSocials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('socials', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('第三方姓名');
            $table->string('email')->comment('第三方Email');
            $table->unsignedSmallInteger('social_type')->comment('第三方類別');
            $table->string('social_type_value')->comment('第三方ID');
            $table->string('image', 2048)->nullable()->comment('第三方照片');
            $table->string('token')->nullable()->comment('第三方token');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('socials');
    }
}
