<?php namespace App\Http\Controllers\profile;

/**
 * @Author: Yehsvant Bhavnasi, Dr. Weijun Luo
 * @Contact: byeshvant@hotmail.com
 * Controller for user profile like listing the use analysis history and editing user and profile viewing etc. in future there is lot of scope of adding other
 * functionlity to this page
 */
use App;
use App\Http\Requests;
use App\User;
use Auth;
use DB;
use Illuminate\Support\Facades\Config;
use Mail;
use Input;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
class ProfileController extends Controller
{

    /**
     * @param $username
     * @return $this
     * method to list user Profile page
     */
    public function user($username)
    {

        $user = User::where('id', '=', $username);
        if ($user->count()) {
            $user = $user->first();
            return view('profile.user')->with('user', $user);
        }
        return App::abort(404);

    }

    /**
     * @param $username
     * @return $this
     * method to list the edit user
     */
    public function edit($username)
    {

        $user = User::where('id', '=', $username);
        if(Auth::user()->id==$username) {
            if ($user->count()) {
                $user = $user->first();
                return view('profile.user_edit')->with('user', $user);
            }

        }

        return App::abort(403);


    }

    /**
     * @return $this
     * ?Method to edit the profile of the user
     */
    public function edit_post()
    {
        $name = $_POST["name"];
        $email = $_POST["email"];
        $id = Auth::user()->id;
        if(Cache::has('users'))
        {
            $users = Cache::get('users');
        }
        else{
            $users = DB::table('users')->select('email')->where('id','!=',$id)->where('email',$email)->orWhereNull('id')->get();
            Cache::put('$users',$users, 10);
        }

        if(sizeof($users) > 0)
        {
            return view('profile.user')->with('error',"Duplicate Email");
        }
        $date = new \DateTime;
        DB::table('users')
            ->where('id', $id)
            ->update(array('name' => $name, 'email' => $email, 'updated_at' => $date));
        return view('profile.user')->with('user', Auth::user())->with('error',"");

    }

    public function post_message()
    {
        $adminEmail =Config::get('app.adminEmail');

        $name = $_POST['name'];
        $me= $_POST['message'];

        $email = $_POST['email'];
        $data['name'] = $name;
        $data['msg'] = htmlspecialchars($me);
        $data['email'] = $email;
        $data['path'] = public_path()."";



        Mail::send('emails.message', $data, function ($message) use ($data) {
            try {

                if(Input::hasFile('uploadimg'))
                {
                    echo "got the image file";

                        $file = Input::file('uploadimg');
                        $filename = $file->getClientOriginalName();

                        $destFile = public_path() . "/" . "all/temp";
                        $file->move($destFile,$filename);
                        $message->attach(public_path()."/all/temp/".$filename, array('as' => $filename));

                }

                $adminEmail =Config::get('app.adminEmail');
                $message->to($adminEmail, "admin")->subject('Message from:'.$data['email']);

            } catch (Exception $e) {
                return "exception in mail";
            }
        });

        return "success";
    }

}
