<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Config;
class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\Inspire',
        'App\Console\Commands\FileMaintainence',
        'App\Console\Commands\RedisClear'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {


        $filePath = public_path()."/logs/output.txt";

        //check if file exists or not if not exists create it

        /*if(!file_exists($filePath)) {
            touch($filePath);
        }*/

        $email =  Config::get("app.adminEmail");
        $schedule->command('inspire')->hourly();
        $schedule->command('fileMaintained')->dailyAt('3:00')->sendOutputTo($filePath)->emailOutputTo([$email]);
        $schedule->command('rm -r '.public_path().'/all/demo/[0-9]*')->dailyAt('3:10')->sendOutputTo($filePath)->emailOutputTo([$email]);
        $schedule->exec('sh '.public_path().'/scripts/biocStatsimport.sh')->weekly()->sendOutputTo($filePath);
        $schedule->exec('sh '.public_path().'/scripts/biocGageStatsimport.sh')->weekly()->sendOutputTo($filePath);
        $schedule->exec('sh '.public_path().'/scripts/KeggDownload.sh')->monthly()->sundays()->at('02:00')->sendOutputTo($filePath)->emailOutputTo($email);
        $schedule->command('redis')->dailyAt('3:11')->sendOutputTo($filePath)->emailOutputTo([$email]);

    }

}
