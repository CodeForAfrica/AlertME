<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProjectsTableGeoDefaults extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE projects ALTER geo_lat SET DEFAULT 450');
        DB::statement('ALTER TABLE projects ALTER geo_lng SET DEFAULT 450');

        DB::statement('ALTER TABLE geocodes ALTER lat SET DEFAULT 450');
        DB::statement('ALTER TABLE geocodes ALTER lng SET DEFAULT 450');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Meh... You shouldn't change anything from this migration.
    }

}
