@extends('GageApp')


@section('content')
    <style>
        .col12{
            width:100%;
            float:left;
            position:relative;
            min-height: 1px;
            padding-left: 15px;
            padding-left: 15px;
        }
        .col8{
            width:1110px;
            float:left;
            position:relative;
            min-height: 1px;
            padding-left: 15px;
            padding-left: 15px;

        }
        .col6{
            width:50%;
            float:left;
            position:relative;
            min-height: 1px;
            padding-left: 15px;
            padding-left: 15px;
        }
        .col4{
            width:300px;
            float:left;
            position:relative;
            min-height: 1px;
            padding-left: 15px;
            padding-left: 15px;
        }

    </style>
    <div class="col12">
        <div class="col12">
            <div class="placeholders ">
                <h1> GAGE Discrete Analysis Results </h1>  </div>


    <?php

    $argument = $_SESSION['argument'];
            $destDir = $_SESSION['destDir'];
            $dir = substr($destDir,strlen("/var/www/Pathway/public/"));
            $contents = scandir($destDir);
                $pathway_list_flag = false;
            foreach($contents as $k => $v)
            {

                if(strcmp($v,"pathways_list.txt") == 0)
                {
                    $sigLines = file($destDir .$v);
                    $pathway_list_flag = true;
                    }

                }
            if($pathway_list_flag)
                {
            echo "<div class='col8'>";
            echo "<div style='width:0.1%'>";
            echo "<a href='#'><span  class = 'glyphicon glyphicon-triangle-bottom' id='expand' style='margin-left:-40px;alignment: right;font-size: 30px;' ></span></a>";
            echo "</div >";
            echo "<div style='width:99%'>";
            echo "<table style='font-size: 14px;margin-bottom: 30px;margin-top:-30px;width=100%;text-align: left;' border=1>";
            echo "<tbody>";
            echo "<th>Pathway List </th>";
                    echo "<th>Ranked Value</th>";
            $lineNumer = 0;
            foreach ($sigLines as $temp) {
                $temp1 = explode(",",$temp);
                if(sizeof($temp1) > 1)
                    {
                echo "<tr>";
                echo "<td>";
                echo $temp1[0];
                echo "</td>";
                echo "<td>";

                        echo "".number_format((float)$temp1[1], 2, '.', '')."";
                echo "</td>";
                echo "</tr>";
}
                $lineNumer  = $lineNumer +1;
                if($lineNumer > 50)
                    {
                        break;
                    }
            }
            echo "</tbody>";
            echo "</table>";
            echo "<h4> <a href ='$dir/pathways_list.txt' target ='_blank'>Click here for full table</a> </h4>";
            echo "</div>";
            echo "</div>";
                    }
                else{
                    echo "Unable to generate pathway List";
                }
    ?>
    </div>
        </div>
    <script>
        $(document).ready(function () {


            $("#expand").click(function () {
                $('.expandable').toggle();


                if ( $( "#expand" ).hasClass( "glyphicon-triangle-bottom" ) ) {
                    $("#expand").addClass('glyphicon-triangle-top').removeClass('glyphicon-triangle-bottom');
                }
                else
                {
                    $("#expand").addClass('glyphicon-triangle-bottom').removeClass('glyphicon-triangle-top');
                }
            });
            $("#sideexpand").click(function () {
                $('#colshow').toggle();
                $('.side-expand').toggle();
                if ( $( "#sideexpand" ).hasClass( "glyphicon-forward" ) ) {
                    $("#sideexpand").addClass('glyphicon-backward').removeClass('glyphicon-forward');
                }
                else
                {
                    $("#sideexpand").addClass('glyphicon-forward').removeClass('glyphicon-backward');
                }
            });
        });
    </script>
@stop
