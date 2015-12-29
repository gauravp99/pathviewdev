<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Exception;

class FileMaintainence extends Command
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'fileMaintained';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'fileMaintained';
	public function handle()
	{

		echo "Started File Maintenance Job:".date('l jS \of F Y h:i:s A');
		//this php files is scheduled in cron to run every day adn look for the user profile if the file size is exceeding the quota assigned to him then it is going to delete the past 5 analysis and an email is send to user.
		/**
		 * Algortihm to maintain files:
		 * 1. look for the users files folders
		 * 2. if the folder size exceed 99 MB of space we are going to delete last 5 analysis
		 * 3. Email send to user
		 * 4. make this analysis to delete all files inside the demo folder all the files in the last date
		 */

		// look for the users
		//path for the users file
		$path = public_path() . "/all/";
		//get all the users
		$users = DB::table('users')->get();
		echo "\n total Number of users in the system: ".sizeof($users);
		//iterate on all users details
		foreach ($users as $userDetails) {

			$user = $userDetails->email;
			//check if file exists or not
			if (file_exists($path . "/" . $user)) {
				//check if the file size is greater than 99 MB

				$io = popen('/usr/bin/du -sk ' . $path . "/" . $user, 'r');
				$size = fgets($io, 4096);
				$size = substr($size, 0, strpos($size, "\t"));
				pclose($io);


				if ($size / 1024 > 99) {
					echo "\nUser: ".$user." folder size is more than 100 MB deleting old files";
					print $path . "/" . $user . " " . "size: " . $size / 1024;
					$directory_Contents = scandir($path . "/" . $user);
					$directory_Contents = array_diff($directory_Contents, array('..', '.'));
					$file_time_Array = array();
					$file_with_timestamp = array();
					foreach ($directory_Contents as $file) {
						array_push($file_with_timestamp, array('filename' => $path . "" . $user . "/" . $file, 'timestamp' => date("d-M-Y H:i:s", filemtime($path . "/" . $user . "/" . $file))));
					}


					//sorting the files with timestamp
					usort($file_with_timestamp, function ($item1, $item2)
					{
						$ts1 = strtotime($item1['timestamp']);
						$ts2 = strtotime($item2['timestamp']);
						return -$ts2 + $ts1;
					});

					//get the files list with space of 50 mb into a list to delete
					$oldestFileList = array();
					$oldestFileListSize = 0;

					foreach ($file_with_timestamp as $file) {
						if ($oldestFileListSize < 50) {
							$f = $file['filename'];
							$io = popen('/usr/bin/du -sk ' . $f, 'r');
							$size = fgets($io, 4096);
							$size = substr($size, 0, strpos($size, "\t"));
							pclose($io);
							$oldestFileListSize += intval($size) / 1024;
							array_push($oldestFileList, $f);
						} else {
							break;
						}

					}

					//delete these files and also remove the record from db

					foreach ($oldestFileList as $toDelFile) {
						$analysisID = substr($toDelFile,strlen($path . "/" . $user."/"));

						//update the record
						try{
							DB::table('analysis')->where('analysis_id', '=', $analysisID)->update(['id' => 0]);
						}
						catch(Exception $e)
						{
							echo "\n exception occured in deleting records from table";
						}

						foreach (scandir($toDelFile) as $file) {
							if ('.' === $file || '..' === $file) continue;
							if (is_dir("$toDelFile/$file")) rmdir_recursive("$toDelFile/$file");
							else unlink("$toDelFile/$file");
						}
						rmdir($toDelFile);
					}



					/*print print_r($file_with_timestamp);
					print print_r($oldestFileList);*/

				}

			}

		}
		echo "\n done removing files File Maintenance Job";
	}
}
		

