<?php namespace App\Commands\Console;


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

		Redis::del('*');
	}

}