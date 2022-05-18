<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author')->constrained('users')->onDelete('cascade')->cascadeOnUpdate();
            $table->tinyInteger('category_id');
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->text('content');
            $table->string('thumbnail', 255)->default('default.jpg');
            $table->tinyInteger('type');
            $table->unsignedInteger('views')->default(0);
            $table->double('rate',2,1)->default(0.0);
            $table->unsignedInteger('raters')->default(0);
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
        Schema::dropIfExists('posts');
    }
}
