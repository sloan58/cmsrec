<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpaceTagToCmsCoSpaces extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cms_co_spaces', function (Blueprint $table) {
            $table->string('space_tag')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cms_co_spaces', function (Blueprint $table) {
            $table->dropColumn('space_tag');
        });
    }
}
