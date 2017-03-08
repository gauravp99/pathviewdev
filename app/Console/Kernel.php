<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Config;
use File;
use Day;
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
     * this program is scheduled to call by cron on every second
     * this program triggers commands if the time scheduled are reached
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {


        //to have the filepath for the commands to put their logs
        $filePath_recent = storage_path()."/logs/recent.log";
        $filePath = storage_path()."/logs/scheduledJobs.log";



        //get the email in config to send the mail to admin
        $email =  Config::get("app.adminEmail");

        //1. Job for file maintenance for users files reaching more than their allocated space runs daily
        $output = $schedule->command('fileMaintained')
            ->dailyAt('3:30')
            ->sendOutputTo($filePath_recent)
            ->emailOutputTo([$email])
            ->then(function (){

                $filePath_recent = storage_path()."/logs/recent.log";
                $filePath = storage_path()."/logs/scheduledJobs.log";

                if(!File::exists($filePath_recent)) {
                    $myfile = fopen($filePath_recent, "w");
                }

                if(!File::exists($filePath)) {
                    $myfile = fopen($filePath, "w");
                }

                // path does not exist
                File::append($filePath, File::get($filePath_recent));
            });

        //2. Job for deleting files in the demo folder for guest users runs daily
        $schedule->exec(public_path().'/scripts/demoFileMaintained.sh '.public_path()."/all/demo/")
            ->dailyAt('3:32')
            ->sendOutputTo($filePath_recent)
            ->emailOutputTo([$email])
            ->then(function (){
                $filePath_recent = storage_path()."/logs/recent.log";
                $filePath = storage_path()."/logs/scheduledJobs.log";
                if(!File::exists($filePath_recent)) {
                    $myfile = fopen($filePath_recent, "w");
                }

                if(!File::exists($filePath)) {
                    $myfile = fopen($filePath, "w");
                }

                File::append($filePath, File::get($filePath_recent));
            });

        //3. Job for getting the statistics of the package usage from bioc website for pathview
        $schedule->exec(public_path().'/scripts/biocStatsimport.sh')
            ->weeklyOn(0,'3:34')
            ->sendOutputTo($filePath_recent)
            ->emailOutputTo([$email])
            ->then(function (){
                $filePath_recent = storage_path()."/logs/recent.log";
                $filePath = storage_path()."/logs/scheduledJobs.log";
                if(!File::exists($filePath_recent)) {
                    $myfile = fopen($filePath_recent, "w");
                }

                if(!File::exists($filePath)) {
                    $myfile = fopen($filePath, "w");
                }

                File::append($filePath, File::get($filePath_recent));
            });

        //4. Job for getting the statistics of the package usage from bioc website for gage
        $schedule->exec(public_path().'/scripts/biocGageStatsimport.sh')
            ->weeklyOn(0,'3:35')
            ->sendOutputTo($filePath_recent)->emailOutputTo([$email])
            ->then(function (){
                $filePath_recent = storage_path()."/logs/recent.log";
                $filePath = storage_path()."/logs/scheduledJobs.log";
                if(!File::exists($filePath_recent)) {
                    $myfile = fopen($filePath_recent, "w");
                }

                if(!File::exists($filePath)) {
                    $myfile = fopen($filePath, "w");
                }

                File::append($filePath, File::get($filePath_recent));
            });

        //5. Kegg script to download most common used images and xml files maintained to have fast  analysis
        $schedule->exec('Rscript '.public_path().'/scripts/update.kegg.R '.public_path())
            ->cron('37 3 7 * *')
            ->sendOutputTo($filePath_recent)
            ->emailOutputTo($email)
            ->then(function (){
                $filePath_recent = storage_path()."/logs/recent.log";
                $filePath = storage_path()."/logs/scheduledJobs.log";
                if(!File::exists($filePath_recent)) {
                    $myfile = fopen($filePath_recent, "w");
                }

                if(!File::exists($filePath)) {
                    $myfile = fopen($filePath, "w");
                }

                File::append($filePath, File::get($filePath_recent));
            });

        //6. Job to clear redis database key values. stored every day for each analysis
        $schedule->command('redis')
            ->dailyAt('3:36')
            ->sendOutputTo($filePath_recent)
            ->emailOutputTo([$email]);
	//7. Job to maintain the species and pathway combination updated with species and pathway list updated
	$schedule->exec(public_path().'/scripts/update.specPath.sh '. public_path())
		 ->cron('54 16 15 1,7 *')
		 ->sendOutputTo($filePath_recent)
		 ->emailOutputTo($email)
		 ->then(function (){
                $filePath_recent = storage_path()."/logs/recent.log";
                $filePath = storage_path()."/logs/scheduledJobs.log";
                if(!File::exists($filePath_recent)) {
                    $myfile = fopen($filePath_recent, "w");
                }

                if(!File::exists($filePath)) {
                    $myfile = fopen($filePath, "w");
                }

                File::append($filePath, File::get($filePath_recent));
            });

    }

}

