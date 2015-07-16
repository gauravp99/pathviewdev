@extends('app')


@section('content')
    <div class="col-md-10">
        <div class="col-md-8">
            <div class="placeholders ">
                <h1> Gage Analysis Results </h1>

                </div>
<?php
                $directory ="";
            $argument = $_SESSION['argument'];

            //split
                $args = array();
            $args = explode(';',$argument);
echo "<table border=1><tbody><tr><th>Argument</th><th>Argument Value</th>";
                foreach($args as &$arg)
                {
                    $keyAndValue = explode(':',$arg);
                    if(sizeof($keyAndValue) == 2 )
                        {

                            if($keyAndValue[0] =='destDir' || $keyAndValue[0] == 'destFile' )
                                {
                                    if($keyAndValue[0] =='destDir')
                                        {
                                    $destDir = $keyAndValue[1];
                                            }
                                }
                            else{
                                echo "<tr><td>".$keyAndValue[0]."</td><td>".$keyAndValue[1]."</td></tr>";
                            }

                            }

                }
echo "</tbody></table>";
            $contents = scandir($destDir);

            if ($contents) {
                foreach ($contents as $key => $value) {
                    if ($value == "." || $value == "..") {
                        unset($key);
                    }
                }
            }


            ?>

        </div>
        <div class="col-md-4">
        <?php
            $dir = substr($destDir,strlen("/var/www/Pathway/public/"));
            foreach ($contents as $k => $v) {


            echo "<li><a target=\"_blank\" href=\"$dir/" . $v . "  \">$v</a></li>";

            }

           echo "<div> <h2> Output/Error Log </h2>";

            $lines = file($destDir . "/errorFile.Rout");
            $flag = false;

            foreach ($lines as $temp) {
                if (strpos($temp, 'directory') == false) {

                    echo "<div style='width:100%;'>" . $temp . "</div>";
                }
            }
                echo "</div>";
            ?>
        </div>
        </div>
@stop