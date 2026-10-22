<?php

namespace Tests\Feature;

use AlertME\Project;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Scout\EngineManager;
use Laravel\Scout\Engines\Engine;
use Mockery;
use Tests\TestCase;

class SearchTest extends TestCase
{
    public function testSearchPreservesPaginationAndTruncatesLongResults(): void
    {
        $project = new Project();
        $project->setRawAttributes([
            'id' => 1,
            'title' => str_repeat('a', 100),
            'description' => str_repeat('b', 250),
        ]);
        $project->setRelation('categories', new Collection());
        /** @var Engine&Mockery\MockInterface $engine */
        $engine = Mockery::mock(Engine::class);
        $engine->shouldReceive('paginate')->once()->andReturn(['nbHits' => 1]);
        $engine->shouldReceive('map')->once()->andReturn(new Collection([$project]));
        $engine->shouldReceive('getTotalCount')->once()->andReturn(1);
        app(EngineManager::class)->extend('test', fn (): Engine => $engine);
        config(['scout.driver' => 'test']);

        $response = $this->get('/search?q=water');

        $response->assertStatus(200);
        $response->assertSee(str_repeat('a', 80) . '...');
        $response->assertSee(str_repeat('b', 200) . '...');
        $response->assertSee('1 results for');
    }
}
