<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmsRecordingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_recordings', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->bigInteger('size');
            $table->dateTime('last_modified');
            $table->integer('downloads')->default(0);
            $table->boolean('shared')->default(false);
            $table->unsignedBigInteger('cms_co_space_id')->index();
            $table->foreign('cms_co_space_id')
                ->references('id')
                ->on('cms_co_spaces')
                ->onDelete('cascade');
            $table->timestamps();
            $table->unique(['filename', 'cms_co_space_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms_recordings');
    }
}
