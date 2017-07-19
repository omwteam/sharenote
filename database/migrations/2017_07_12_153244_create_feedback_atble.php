<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbackAtble extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',60);
            $table->text('content');
            $table->tinyInteger('type');
            $table->string('contact')->nullable();
            $table->tinyInteger('active')->default(0)->comment('跟进状态默认01、表示已经接受 2、表示处理中3、处理完');
            $table->ipAddress('ip');
            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('feedback');
    }
}
