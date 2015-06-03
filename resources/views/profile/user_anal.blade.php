@extends('app')
@section('content')
    @include('navigation')


    <div class='col-md-2-result sidebar col-md-offset-2'>
        <h1 class="success" style="color:rgb(65, 134, 58);">{{$user->name}} profile</h1>

        <p>User ID: {{ $user->id }}</p>

        <p>User Email: {{ $user->email }}</p>

        <p>User Created at: {{ $user->created_at }}</p>
        <?php
        $f = './all/' . $user->email;
        $io = popen('/usr/bin/du -sh ' . $f, 'r');
        $size = fgets($io, 4096);
        $size = substr($size, 0, strpos($size, "\t"));

        pclose($io);
        $size = 100 - intval($size);
        if($size < 10)
        {
        ?>
        <p class="alert alert-danger"><b>Remaining space: {{ $size}} MB </b></p>
        <?php } else { ?>
        <p class="alert alert-info"><b>Remaining space: {{ $size}} MB </b></p>

        <?php }?>

        <h2>Recent activity: </h2>
        <?php
        $analyses = DB::table('analyses')->where('id', $user->id)->get();

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
        } else {?>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>Analysis Type</th>
                <th>Number of days</th>
                <th>Click Here</th>
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
                echo "<td><p> Click to see results  at folder$analyses1->analysis_id <a href=/anal_hist?directory=$directory&dir=$dir&id=$id&suffix=$suffix>click here</a> </p></td></tr>";


                //echo "$analyses1->arguments";
            }
            }?>
        </table>
    </div>

@stop