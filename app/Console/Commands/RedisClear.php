<?php namespace App\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
class RedisClear extends Command {

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */

	protected $name = 'redis';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Delete all the keys,value data every day';
	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		echo "Started the redis clear".date('l jS \of F Y h:i:s A');
		Redis::flushAll();

		echo "\nDeleted redis configurations";
	}

}
