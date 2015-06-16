@extends('app')

@section('content')

    @include('navigation')



    <div class='col-md-2-result sidebar col-md-offset-2'>
        <h1 class="success" style="color:rgb(65, 134, 58);">{{Auth::user()->name}} profile</h1>
        <?php
        function xcopy($source, $dest, $permissions = 0755)
        {
            // Check for symlinks
            if (is_link($source)) {
                return symlink(readlink($source), $dest);
            }

            // Simple copy for a file
            if (is_file($source)) {
                return copy($source, $dest);
            }

            // Make destination directory
            if (!is_dir($dest)) {
                mkdir($dest, $permissions);
            }

            // Loop through the folder
            $dir = dir($source);
            while (false !== $entry = $dir->read()) {
                // Skip pointers
                if ($entry == '.' || $entry == '..') {
                    continue;
                }

                // Deep copy directories
                xcopy("$source/$entry", "$dest/$entry", $permissions);
            }

            // Clean up
            $dir->close();
            return true;
        }
        ?>
        @if(Session::get('anal_id')!=NULL)
            <?php
                if(!file_exists(public_path().'/all/'.Auth::user()->email))
                    {
                mkdir(public_path().'/all/'.Auth::user()->email);
                }

            xcopy(public_path().'/all/demo/'.Session::get('anal_id'),public_path().'/all/'.Auth::user()->email.'/'.Session::get('anal_id'));
            DB::table('analyses')
                    ->where('analysis_id', Session::get('anal_id'))
                    ->update(array('id' => Auth::user()->id));
            Session::forget('anal_id');
            ?>

        @endif
        <div class="col-md-12">
        <div class="col-md-6">
        @if(Session::get('error')!=NULL)

            <p class="alert alert-danger">
                You don't have enough space to do more analysis
            </p>

        @endif
        <p>User ID: {{Auth::user()->id }}</p>

        <p>User Email: {{Auth::user()->email }}</p>

        <p>User Created at: {{Auth::user()->created_at }}</p>
        <?php
        $f = './all/' . Auth::user()->email;
        $io = popen('/usr/bin/du -sh ' . $f, 'r');
        $size = fgets($io, 4096);
        $size = substr($size, 0, strpos($size, "\t"));

        pclose($io);
        $size = 100 - intval($size);
        if($size < 10)
        {
        ?>
        <p class="alert alert-danger"><b>Remaining space:  {{ $size}} MB </b></p>
        <?php } else { ?>
        <p ><b>Remaining space:  {{ $size}} MB </b></p>

        <?php }?>
        </div>
            <div class="col-md-6">
        <div class="col-md-12">
            <a href="/edit_user/{{Auth::user()->id}}"><input type="button" class="styled-button-2" value="Edit Profile"/></a>
        </div>
            <div class="col-md-12">
                <a href="/passwordReset"><input type="button" class="styled-button-2" value="Change Password"/></a>
            </div>
            </div>
        </div>
        <h2>Recent activity: </h2>
        <?php
        $analyses = DB::table('analyses')->where('id', Auth::user()->id)->get();

        function get_string_between($string, $start, $end)
        {
            $string = " " . $string;
            $ini = strpos($string, $start);
            if ($ini == 0) return "";
            $ini += strlen($start);
            $len = strpos($string, $end, $ini) - $ini;
            return substr($string, $ini, $len);
        }
        if (empty($analyses)) {
            echo "No recenet Activity's";
        } else { ?>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>Analysis Type</th>
                <th>Number of days</th>
                <th>Result access</th>
            </tr>
            </thead>
            <?php
            $i = 1;
            foreach ($analyses as $analyses1) {
                $now = time(); // or your date as well
                $your_date = strtotime(str_split($analyses1->created_at, 10)[0]);
                $date_diff = $now - $your_date;

                echo "<tr><td>$i</td><td><h4> $analyses1->analysis_type </h4></td>";
                $i = $i + 1;
                echo "<td> " . floor($date_diff / (60 * 60 * 24)) . " days ago ";
                $directory = get_string_between($analyses1->arguments, "targedir:", ",");
                $dir = get_string_between($analyses1->arguments, public_path(), ",");
                $id = get_string_between($analyses1->arguments, "species:", ",") . get_string_between($analyses1->arguments, "pathway:", ",");
                $suffix = get_string_between($analyses1->arguments, "suffix:", ",");
                echo "</td>";
               // echo "<td><p>  <a href=/anal_hist?directory=$directory&dir=$dir&id=$id&suffix=$suffix>Analysis : $analyses1->analysis_id</a> </p></td></tr>";
                echo "<td><p>  <a href=/anal_hist?analyses=$analyses1->analysis_id&id=$id&suffix=$suffix>Analysis $analyses1->analysis_id</a> </p></td></tr>";


                //echo "$analyses1->arguments";
            }
            }?>
        </table>

    </div>

@stop
