<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('content')->nullable()->comment('编辑器处理后的内容');
            $table->text('origin_content')->nullable()->comment('原始内容');
            $table->integer('u_id');
            $table->integer('f_id');
            $table->enum('isPrivate',[0,1])->default(1)->comment('0表示私有，1表示公开');
            $table->enum('type',[1,2])->default(1)->comment('1表示md，2表示普通');
            $table->enum('active',[0,1])->default(1)->comment('1表示课件，0表示不可见');
            $table->integer('export_count')->default(0)->comment('导出次数');
            $table->integer('explore_count')->default(0)->comment('浏览次数');
            $table->integer('updated_id')->nullable()->comment('最后更新ID');
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
        Schema::dropIfExists('notes');
    }
}
