<?php namespace App\Console;

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
        'App\Console\Commands\Inspire',
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
        $filePath = "/var/www/Pathway/public/data/output.txt";
        $schedule->exec('sh /var/www/Pathway/public/scripts/biocStatsimport.sh')->hourly()->sendOutputTo($filePath);
        $schedule->exec('sh /var/www/Pathway/public/scripts/biocGageStatsimport.sh')->hourly()->sendOutputTo($filePath);
        $schedule->exec('sh /var/www/Pathway/public/scripts/FileMaintain_new.sh')->twiceDaily()->sendOutputTo($filePath);
        $schedule->exec('sh /var/www/Pathway/public/scripts/KeggDownload.sh')->monthly()->sundays()->at('02:00')->sendOutputTo($filePath);
    }

}
