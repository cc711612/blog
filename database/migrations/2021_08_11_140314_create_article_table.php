<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->nullable()->comment('user 的流水號');
            $table->text('title')->nullable()->comment('標題');
            $table->text('content')->nullable()->comment('內容');
            $table->unsignedSmallInteger('status')->default(0)->comment('狀態');
            $table->unsignedBigInteger('created_by')->nullable()->comment('建立者ID');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('修改者ID');
            $table->unsignedBigInteger('deleted_by')->nullable()->comment('刪除者ID');
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
        Schema::dropIfExists('articles');
    }
}
