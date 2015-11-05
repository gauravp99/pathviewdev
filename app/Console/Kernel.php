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
        $filePath = "/var/www/PathwayWeb/public/storage/logs/output.txt";
        $schedule->command('inspire')->hourly();
        $schedule->command('fileMaintained')->dailyAt("3:10")->sendOutputTo($filePath)->emailOutputTo(['byeshvant@gmail.com']);
        $schedule->command('rm -r /var/www/PathwayWeb/public/all/demo/[0-9]*')->dailyAt("3:10")->sendOutputTo($filePath)->emailOutputTo(['byeshvant@gmail.com']);
        #$schedule->exec('. ./var/www/PathwayWeb/public/scripts/ProcessStatus.sh')->at("12:47")->sendOutputTo($filePath)->emailOutputTo(['byeshvant@gmail.com']);
        $schedule->exec('sh /var/www/PathwayWeb/public/scripts/biocStatsimport.sh')->hourly()->sendOutputTo($filePath);
        $schedule->exec('sh /var/www/PathwayWeb/public/scripts/biocGageStatsimport.sh')->hourly()->sendOutputTo($filePath);
        //$schedule->exec('sh /var/www/Pathway/public/scripts/FileMaintain_new.sh')->twiceDaily()->sendOutputTo($filePath);
        $schedule->exec('sh /var/www/PathwayWeb/public/scripts/KeggDownload.sh')->monthly()->sundays()->at('02:00')->sendOutputTo($filePath)->emailOutputTo(['byeshvant@gmail.com']);

        #$schedule->command('redis')->dailyAt("03:00");
        $schedule->command('redis')->dailyAt("3:00")->sendOutputTo($filePath)->emailOutputTo(['byeshvant@gmail.com']);

    }

}
