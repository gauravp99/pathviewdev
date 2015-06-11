@extends('app')
@section('content')

    @include('navigation')
    <div class="row1 placeholders">
        <?php
        $contents = scandir($_GET['directory']);
        $directory1 = $_GET['dir'];

        if ($contents) {
            foreach ($contents as $key => $value) {
                if ($value == "." || $value == "..") {
                    unset($key);

                }
            }
        }
        echo "<ul>";
        $val = "";
        $id = $_GET['id'];
        $id = substr($id, 0, 8);
        foreach ($contents as $k => $v) {

            if (strpos($v, 'log') !== false || strpos($v, "" . $id . ".txt"))
                echo "<li><a target=\"_blank\" href=\"$directory1/" . $v . "  \">$v</a></li>";

        }
        echo "</ul>";
$analysis_id= explode("/",$directory1);

$analysis = $analysis_id[sizeof($analysis_id)-1];
        foreach ($contents as $k => $v) {
            //echo $k;
            if (strpos($v, $_GET['suffix'])) {
                $val = $v;

                $id = substr($val, 0, 8);
                if (strpos($val, 'png')) {
                    echo "<li>Click here to view the pathview  <a target=\"_blank\" href=\"viewer?analyses=$analysis&id=$id&image=$directory1/$val\" > " . $val . "</a>";

                } else
                    echo "<li>Click here to view the pathview  <a target=\"_blank\" href=\"$directory1/" . $val . "  \">$val</a></li>";


            }
        }



        ?>
    </div>
@stop