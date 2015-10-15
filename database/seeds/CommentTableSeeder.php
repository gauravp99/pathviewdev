<?php // app/database/seeds/CommentTableSeeder.php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\comment;
class CommentTableSeeder extends Seeder {

    public function run()
    {
        DB::table('comments')->delete();
    
        comment::create(array(
            'author' => 'Chris Sevilleja',
            'text_string' => 'Look I am a test comment.',
	    'user_email' => 'byeshvant@hotmail.com.',
	    'issue_id' => '1'
        ));
    
        comment::create(array(
            'author' => 'Nick Cerminara',
            'text_string' => 'This is going to be super crazy.',
	        'user_email' => 'b@hotmail.com.',
            'issue_id' => '2'

        ));
    
        comment::create(array(
            'author' => 'Holly Lloyd',
            'text_string' => 'I am a master of Laravel and Angular.',
	        'user_email' => 'bhg@hotmail.com.',
            'issue_id' => '1'
        ));
    }    

}
