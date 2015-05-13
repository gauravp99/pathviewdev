@extends('app')

@section('content')

    @include('navigation')



    <div class='col-md-2-result sidebar col-md-offset-2'>
        <h1 class="success" style="color:rgb(65, 134, 58);">{{Auth::user()->name}} profile</h1>
        <p>User ID: {{Auth::user()->id }}</p>
        <p>User Email: {{Auth::user()->email }}</p>
        <p>User Created at: {{Auth::user()->created_at }}</p>
        <h2>Recent activity: </h2>
        <?php
        $analyses = DB::table('analyses')->where('id',Auth::user()->id)->get();
        if (empty($analyses)) {
            echo "No recenet Activity's";
        }
        function get_string_between($string, $start, $end)
        {
            $string = " " . $string;
            $ini = strpos($string, $start);
            if ($ini == 0) return "";
            $ini += strlen($start);
            $len = strpos($string, $end, $ini) - $ini;
            return substr($string, $ini, $len);
        }?>
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
            $i=1;
            foreach ($analyses as $analyses1) {
                $now = time(); // or your date as well
                $your_date = strtotime(str_split($analyses1->created_at, 10)[0]);
                $date_diff = $now - $your_date;

                echo "<tr><td>$i</td><td><h4> $analyses1->analysis_type </h4></td>";
                $i = $i+1;
                echo "<td> ".floor($date_diff / (60 * 60 * 24)) . " days ago ";
                $directory = get_string_between($analyses1->arguments, "targedir:", ",");
                $dir = get_string_between($analyses1->arguments, public_path(), ",");
                $id = get_string_between($analyses1->arguments, "species:", ",") . get_string_between($analyses1->arguments, "pathway:", ",");
                $suffix = get_string_between($analyses1->arguments, "suffix:", ",");
                echo "</td>";
                echo "<td><p> Click to see results  at folder$analyses1->analysis_id <a href=/anal_hist?directory=$directory&dir=$dir&id=$id&suffix=$suffix>click here</a> </p></td></tr>";


                //echo "$analyses1->arguments";
            }
            ?>
        </table>
    </div>

@stop
