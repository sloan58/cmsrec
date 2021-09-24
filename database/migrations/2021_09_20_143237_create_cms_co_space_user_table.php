<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCmsCoSpaceUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_co_space_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cms_co_space_id')->index();
            $table->foreign('cms_co_space_id')
                ->references('id')
                ->on('cms_co_spaces')
                ->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->boolean('admin_assigned')->default(false);
            $table->unique(['cms_co_space_id', 'user_id']);
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
        Schema::dropIfExists('cms_co_space_user');
    }
}
