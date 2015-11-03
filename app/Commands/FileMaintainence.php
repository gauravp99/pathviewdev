<?php namespace App\Commands;

use App\Commands\Command;
use DB;
use App\Http\Models\DbDataFetch;
use Illuminate\Contracts\Bus\SelfHandling;

/*class FileMaintainence extends Command implements SelfHandling {
	public function __construct()
	{
		//
	}

	public function handle()
	{
*/

		//this php files is scheduled in cron to run every day adn look for the user profile if the file size is exceeding the quota assigned to him then it is going to delete the past 5 analysis and an email is send to user.
	/**
		Algortihm to maintain files:
		1. look for the users files folders
		2. if the folder size exceed 99 MB of space we are going to delete last 5 analysis
		3. Email send to user
		4. make this analysis to delete all files inside the demo folder all the files in the last date
		*/
		
	// look for the users
	#$path = public_path()."/all";
	$path = "/var/www/PathwayWeb/public/all/";
	#$users = DB::table('users')->get();
	$users = array("ASDF@ASDF.COM","byeshvant@ho","byeshvant@hotmail.com","ybhavnas@uncc.edu");
	print $users[1];
	foreach( $users as $user)
	{
		
		if(file_exists($path."/".$user))
		{
		 	$directory_Contents = scandir($path."/".$user);
			$directory_Contents = array_diff($directory_Contents, array('..', '.'));
			$file_time_Array = array();
			$file_with_timestamp = array();
			foreach($directory_Contents as $file)
			{
			//print $file;
			#print filemtime($path."/".$user."/".$file);
   			array_push($file_with_timestamp,date ("d-M-Y H:i:s",filemtime($path."/".$user."/".$file)));
				
			}	
			print print_r($file_with_timestamp);	
		}
		
	}
		
	
		
/*
	}

}*/
