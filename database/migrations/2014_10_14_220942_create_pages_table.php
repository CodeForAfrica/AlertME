<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
        });

        // Add First Page
        $page = new \AlertME\Page();
        $page->slug = 'about';
        $page->title = 'About';
        $page->description = 'South African law says planned development projects, including mines, dams, power stations, roads and landfill sites, need to have their environmental impacts assessed before they can go ahead.

\#GreenAlert helps you to find out what Environmental Impact Assessments (EIAs) are happening in your area.

Find your location to see the details of an EIA: its official ID, the project description, status of the development, and the government body responsible for authorising and monitoring the development.

You can keep up to date with the changing status of EIAs that interest you by registering for personalised alerts. We will send you real-time notifications by email or SMS.

And you can help keep the developers accountable by joining and participating in our community network.


\#GreenAlert is a project by [Oxpeckers](http://oxpeckers.org)';
        $page->save();
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pages');
    }

}
