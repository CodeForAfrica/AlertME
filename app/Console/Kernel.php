<<<<<<< HEAD
<?php namespace Greenalert\Console;
=======
<?php

namespace App\Console;
>>>>>>> ff441abd622893752ffc1ba58ce64200606d07ff

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

<<<<<<< HEAD
class Kernel extends ConsoleKernel {

=======
class Kernel extends ConsoleKernel
{
>>>>>>> ff441abd622893752ffc1ba58ce64200606d07ff
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
<<<<<<< HEAD
        'Greenalert\Console\Commands\Inspire',
        'Greenalert\Console\Commands\PahaliUpdate',
        'Greenalert\Console\Commands\PahaliUpgrade',
        // TODO: Pahali clean scrapes.
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
            ->hourly();
    }
=======
        \App\Console\Commands\Inspire::class,
    ];
>>>>>>> ff441abd622893752ffc1ba58ce64200606d07ff

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();
    }
}
