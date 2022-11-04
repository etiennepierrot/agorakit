<?php

use Illuminate\Database\Migrations\Migration;

class AddGaIdToGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('groups', function ($table) {
            if (!Schema::hasColumn('groups', 'ga_id')) {
                $table->string('ga_id')-> nullable();
                $table->unique(['ga_id']);
                $table->index(['ga_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groups', function ($table) {
            $table->dropColumn('ga_id');
        });
    }
}
