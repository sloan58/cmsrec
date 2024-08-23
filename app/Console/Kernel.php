<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         // Check for new files on disk not imported into the DB
         $schedule->command('cmsrec:scan-for-new')->everyFiveMinutes();

         // Sync with the CMS API to pull in Users and CoSpaces
         $schedule->command('cmsrec:sync')->everyThirtyMinutes();

         // Check that the NFS mount exists (if configured)
         $schedule->command('cmsrec:monitor-nfs')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
