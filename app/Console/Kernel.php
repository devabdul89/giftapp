<?php

namespace App\Console;

use App\Console\Commands\MakeRepositoryCommand;
use App\Console\Commands\ProcessEventsCommand;
use App\Console\Commands\SendMailsToAwaitingMembers;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\RequestMakeCommand;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        MakeRepositoryCommand::class,
        RequestMakeCommand::class,
        ProcessEventsCommand::class,
        SendMailsToAwaitingMembers::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
