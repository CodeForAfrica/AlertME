<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HomeCompatibilityTest extends TestCase
{
    public function testHomeRendersWithStoredContentAndNoProjects(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        Schema::create('pages', static function (Blueprint $table): void {
            $table->increments('id');
            $table->text('data');
        });
        Schema::create('syncs', static function (Blueprint $table): void {
            $table->increments('id');
            $table->timestamps();
        });
        foreach (['projects', 'subscriptions', 'users'] as $tableName) {
            Schema::create($tableName, static function (Blueprint $table): void {
                $table->increments('id');
                $table->softDeletes();
            });
        }
        DB::table('pages')->insert(['id' => 1, 'data' => json_encode([
            'banner' => ['title' => 'Track local projects', 'description' => 'Public information'],
            'how' => ['title' => 'How it works', 'blurbs' => array_fill(0, 3, ['description' => '**Find projects**'])],
        ])]);
        DB::table('syncs')->insert(['updated_at' => '2026-01-01 12:00:00']);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Track local projects');
        $response->assertSee('<strong>Find projects</strong>', false);
    }
}
