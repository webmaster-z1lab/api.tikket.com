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
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('entrance:update-lots')
                  ->dailyAt('00:00');

        $schedule->command('event:upcoming')
            ->dailyAt('08:00');

        $schedule->command('event:diary-report')
            ->dailyAt('08:00');

        $schedule->command('entrance:running-out-lot')
            ->dailyAt('08:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        $this->load(base_path('Modules/Event/Console'));

        require base_path('routes/console.php');
    }
}
