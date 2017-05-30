<?php

use AlertME\Page;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeCustomPagesContent extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $about = Page::find(1);
        DB::table('pages')->truncate();
        Schema::table('pages', function (Blueprint $table) {
            //
            $table->dropColumn('title');
            $table->dropColumn('description');
            $table->longText('data')->nullable();
        });

        // Create Home Page
        $page = new Page;
        $page->slug = '/';
        $page->data = array(
            'banner' => array(
                'title'       => 'Find Environmental Impact Assessments Near You',
                'description' => 'And register for alerts in your area...'
            ),
            'how'    => array(
                'title'  => 'How '.env('APP_NAME','#AlertME').' Works',
                'blurbs' => array(
                    array(
                        'description' => 'Find Environmental Impact Assessments happening near you.'
                    ),
                    array(
                        'description' => 'Register for alerts in your area to get updates of new or current EIAs.'
                    ),
                    array(
                        'description' => 'Take action by getting your friends involved in signing a petition.'
                    )
                )
            )
        );
        $page->save();

        // Create About Page
        $page = new Page;
        $page->slug = 'about';
        $page->data = array(
            'title'       => $about->title,
            'description' => $about->description
        );
        $page->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $about = Page::find(2);
        DB::table('pages')->truncate();
        Schema::table('pages', function (Blueprint $table) {
            //
            $table->dropColumn('data');
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
        });
        // Add Page using Eloquent
        $page = new Page;
        $page->slug = 'about';
        $page->title = $about->data->title;
        $page->description = $about->data->description;
        $page->save();
    }

}
