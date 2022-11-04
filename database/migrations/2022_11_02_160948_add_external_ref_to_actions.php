<?php

use Illuminate\Database\Migrations\Migration;

class AddExternalRefToActions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('actions', function ($table) {
            if (!Schema::hasColumn('actions', 'external_ref')) {
                $table->string('external_ref')-> nullable();
                $table->unique(['external_ref']);
                $table->index(['external_ref']);
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
        Schema::table('actions', function ($table) {
            $table->dropColumn('external_ref');
        });
    }
}
