<?php

namespace Tests\Feature;

use AlertME\Page;
use AlertME\Project;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CompatibilityTest extends TestCase
{
    public function testProjectNormalizesImportedTitles(): void
    {
        $project = new Project();
        $project->title = 'NEW WATER PROJECT';
        $project->description = 'NEW WATER SUPPLY';

        $this->assertSame('New Water Project', $project->title);
        $this->assertSame('New water supply', $project->description);
    }

    public function testProjectPreservesFallbacksForEmptyImports(): void
    {
        $project = new Project();
        $project->title = '';
        $project->description = '';

        $this->assertSame('[No Title]', $project->title);
        $this->assertSame('[No Description]', $project->description);
    }

    public function testPageDataRoundTripsStructuredContent(): void
    {
        $page = new Page();
        $page->data = ['title' => 'About', 'description' => '**Public information**'];

        $this->assertSame('About', $page->data->title);
        $this->assertSame('**Public information**', $page->data->description);
    }

    public function testAboutPageRendersStoredMarkdown(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        Schema::create('pages', static function (Blueprint $table): void {
            $table->increments('id');
            $table->text('data');
        });
        \DB::table('pages')->insert([
            'id' => 2,
            'data' => json_encode(['title' => 'About this service', 'description' => '**Public information**']),
        ]);

        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertSee('<strong>Public information</strong>', false);
    }

    public function testDashboardRequiresAuthentication(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }
}
