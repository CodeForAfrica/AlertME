<?php
// Exercise an empty-category map so the PHP 8 count(null) failure cannot return after the runtime upgrade.


namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MapTest extends TestCase
{
    public function testMapRendersProjectsWithoutAssignedCategories(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        Schema::create('projects', static function (Blueprint $table): void {
            $table->increments('id');
            foreach (['data_id', 'title', 'description', 'geo_type', 'geo_address', 'status'] as $column) {
                $table->string($column)->default('');
            }
            $table->double('geo_lat');
            $table->double('geo_lng');
        });
        Schema::create('geocodes', static function (Blueprint $table): void {
            $table->string('address');
            $table->double('lat');
            $table->double('lng');
            $table->string('status');
        });
        Schema::create('project_category', static function (Blueprint $table): void {
            $table->integer('project_id');
            $table->integer('category_id');
        });
        DB::table('projects')->insert(['geo_type' => 'lat_lng', 'geo_lat' => 0, 'geo_lng' => 0]);

        $response = $this->get('/map');

        $response->assertStatus(200);
        $response->assertSee('pahali.categories.set( [] );', false);
    }
}
