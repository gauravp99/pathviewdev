@extends('GageApp')


@section('content')
    <style>
        .col12 {
            width: 100%;
            float: left;
            position: relative;
            min-height: 1px;
            padding-left: 15px;
            padding-left: 15px;
        }

        .col8 {
            width: 1110px;
            float: left;
            position: relative;
            min-height: 1px;
            padding-left: 15px;
            padding-left: 15px;

        }

        .col6 {
            width: 50%;
            float: left;
            position: relative;
            min-height: 1px;
            padding-left: 15px;
            padding-left: 15px;
        }

        .col4 {
            width: 300px;
            float: left;
            position: relative;
            min-height: 1px;
            padding-left: 15px;
            padding-left: 15px;
        }

    </style>
    <div class="col12">
        <div class="col12">
            <div class="placeholders ">
                <h1> GAGE Discrete Analysis Results </h1></div>


            <?php
            echo "<h2> Analysis ID: " . $_SESSION['analysis_id'] . "</h2>";
            $argument = $_SESSION['argument'];
            $destDir = $_SESSION['destDir'];
            $dir = substr($destDir, strlen(public_path()));
            $user = "demo";
            if (Auth::user()) {
                $user = Auth::user()->email;
            }
            $anal_id = substr($dir, strlen("/all/" . $user . "/"), strlen($dir) - 1);
            $contents = scandir($destDir);
            $pathway_list_flag = false;
            $pathway_significant_flag = false;
            foreach ($contents as $k => $v) {

                if (strcmp($v, "discrete.res.txt") == 0) {
                    $sigLines = file($destDir . $v);
                    $pathway_list_flag = true;
                } else if (strcmp($v, "discrete.sig.txt") == 0) {
                    $pathway_significant_flag = true;
                }

            }
            $rootPath = realpath($destDir);

            chdir($destDir);
            // Initialize archive object
            $zip = new ZipArchive();
            $zip->open('file.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

            // Create recursive directory iterator
            /** @var SplFileInfo[] $files */
            $files = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($rootPath),
                    RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file) {
                // Skip directories (they would be added automatically)
                if (!$file->isDir()) {

                    // Get real and relative path for current file
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($rootPath) + 1);

                    // Add current file to archive
                    if (!strpos($filePath, 'RData') && !strpos($filePath, 'Rout'))
                        $zip->addFile($filePath, $relativePath);

                }
            }

            // Zip archive will be created only after closing object
            $zip->close();





            if($pathway_significant_flag)
            {
                echo "<div class='col8'>";


                echo "<h2 class='alert alert-info'> Click here to download the zipped output files <a href=" . $dir . "/file.zip target='_blank'>File.zip</a></h2>";
                echo "<div style='width:99%'>";
                echo "<div style='width:0.1%'>";
                echo "<a href='#'><span  class = 'glyphicon glyphicon-triangle-bottom' id='expand' style='margin-left:-40px;alignment: right;font-size: 30px;' ></span></a>";
                echo "</div >";
                echo "<table style='font-size: 14px;margin-bottom: 30px;width=100%;text-align: left;' border=1>";
                echo "<tbody>";

                $lineNumer = 0;
                $integer_array = array();
                foreach ($sigLines as $temp) {
                    if ($lineNumer > 0) {
                        $temp1 = explode("\t", $temp);
                        if (sizeof($temp1) > 1) {
                            if ($lineNumer > 10) {
                                if ($lineNumer == 50) {
                                    break;
                                }
                                echo "<tr class='expandable'>";
                            } else {
                                echo "<tr>";
                            }
                            echo "<td>";
                            echo $temp1[0];
                            echo "</td>";
                            $temp1 = array_diff($temp1, array($temp1[0]));
                            $i = 0;

                            foreach ($temp1 as $temp) {
                                echo "<td>";
                                if (in_array($i + 1, $integer_array)) {
                                    echo "" . $temp . "";
                                } else {
                                    echo "" . number_format((float)$temp, 2, '.', '') . "";
                                }

                                echo "</td>";
                                $i++;
                            }
                            echo "</tr>";
                        }
                    } else {
                        $temp1 = explode("\t", $temp);
                        echo "<tr>";
                        echo "<th>";
                        echo "Signifcant Pathway ID";
                        echo "</th>";
                        $i = 1;

                        foreach ($temp1 as $temp) {
                            echo "<th>";
                            if (strcmp($temp, "set.size") == 0 || strcmp($temp, "hits") == 0 || strcmp($temp, "selected") == 0 || strcmp($temp, "hits.bg") == 0 || strcmp($temp, "background") == 0) {
                                array_push($integer_array, $i);
                            }
                            $i++;
                            echo $temp;
                            echo "</th>";
                        }

                        echo "</tr>";
                    }

                    $lineNumer = $lineNumer + 1;
                    if ($lineNumer > 50) {
                        break;
                    }
                }
                echo "</tbody>";
                echo "</table>";
                echo "<h4> <a href ='$dir/discrete.res.txt' target ='_blank'>Click here for full table</a> </h4>";

            ?>
            <div class="col12">
                <div class="col6">
                    <div class="col-md-4">
            <?php

            //split
            $args = array();
            $args = explode(';',$argument);
            echo "<h3>User Input Values</h3>";
            echo "<table border=1><tbody><tr><th>Argument</th><th>Argument Value</th>";
            foreach($args as &$arg)
            {
                $keyAndValue = explode(':',$arg);
                if(sizeof($keyAndValue) == 2 )
                {

                    if($keyAndValue[0] =='destDir' || $keyAndValue[0] == 'destFile' )
                    {
                        continue;
                    }
                    else{
                        echo "<tr><td>".$keyAndValue[0]."</td><td>".$keyAndValue[1]."</td></tr>";
                    }

                }

            }
            echo "</tbody></table>";?>
        </div>
    </div>
    <div class="col6">
        <?php
        foreach ($contents as $k => $v) {

            if (strpos($v, ".png") > -1) {
                if (Auth::user()) {
                    $id = substr($dir, strlen('all/' . Auth::user()->email . '/'));
                } else {

                    $id = substr($dir, strlen('all/demo/'));
                }


                echo "<div class = 'col12  pdf' >";
                echo "<a href='/pathviewViewer?id=$id&image=$v' target='_blank'><img class='pdf-info' width='500px' height='500px' src=" . $dir . $v . "></a>";
                echo "<p class='pdf-info' style='align:center;'>" . $v . "</p>";
                echo "</div>";
            }

        }
        echo "</div>";
        echo "</div>";



                echo "</div>";
                echo "</div>";


            }
            else if ($pathway_list_flag) {
            echo "<div class='col8'>";
            echo "<h2>There are no significant pathways, Here is the complete Pathways List </h2>";
            echo "<div style='width:0.1%'>";
            // echo "<a href='#'><span  class = 'glyphicon glyphicon-triangle-bottom' id='expand' style='margin-left:-40px;alignment: right;font-size: 30px;' ></span></a>";
            echo "</div >";
            echo "<div style='width:99%'>";

            echo "<table style='font-size: 14px;margin-bottom: 30px;margin-top:0px;width=100%;text-align: left;' border=1>";
            echo "<tbody>";

            $lineNumer = 0;
            $integer_array = array();
            foreach ($sigLines as $temp) {
                if ($lineNumer > 0) {
                    $temp1 = explode("\t", $temp);
                    if (sizeof($temp1) > 1) {
                        if ($lineNumer > 10) {
                            if ($lineNumer == 50) {
                                break;
                            }
                            echo "<tr class='expandable'>";
                        } else {
                            echo "<tr>";
                        }
                        echo "<td>";
                        echo $temp1[0];
                        echo "</td>";
                        $temp1 = array_diff($temp1, array($temp1[0]));
                        $i = 0;
                        foreach ($temp1 as $temp) {
                            echo "<td>";
                            if (in_array($i + 1, $integer_array)) {
                                echo "" . $temp . "";
                            } else {
                                echo "" . number_format((float)$temp, 2, '.', '') . "";
                            }
                            echo "</td>";
                            $i++;
                        }


                        echo "</tr>";
                    }
                } else {
                    $temp1 = explode("\t", $temp);


                    echo "<th>";
                    echo "pathway ID";
                    echo "</th>";
                    $i = 1;

                    foreach ($temp1 as $temp) {
                        echo "<th>";
                        if (strcmp($temp, "set.size") == 0 || strcmp($temp, "hits") == 0 || strcmp($temp, "selected") == 0 || strcmp($temp, "hits.bg") == 0 || strcmp($temp, "background") == 0) {
                            array_push($integer_array, $i);
                        }
                        $i++;
                        echo $temp;
                        echo "</th>";
                    }


                    echo "</tr>";
                }

                $lineNumer = $lineNumer + 1;

            }
            echo "</tbody>";
            echo "</table>";
            echo "<h4> <a href ='$dir/discrete.res.txt' target ='_blank'>Click here for full table</a> </h4>";
            ?>

            <div class="col12">
                <div class="col6">
                    <div class="col-md-4">
                        <?php

                        //split
                        $args = array();
                        $args = explode(';',$argument);
                        echo "<h3>User Input Values</h3>";
                        echo "<table border=1><tbody><tr><th>Argument</th><th>Argument Value</th>";
                        foreach($args as &$arg)
                        {
                            $keyAndValue = explode(':',$arg);
                            if(sizeof($keyAndValue) == 2 )
                            {

                                if($keyAndValue[0] =='destDir' || $keyAndValue[0] == 'destFile' )
                                {
                                    continue;
                                }
                                else{
                                    echo "<tr><td>".$keyAndValue[0]."</td><td>".$keyAndValue[1]."</td></tr>";
                                }

                            }

                        }
                        echo "</tbody></table>";?>
                    </div>
                </div>
                <div class="col6">
                    <?php
                    foreach ($contents as $k => $v) {

                        if (strpos($v, ".png") > -1) {
                            if (Auth::user()) {
                                $id = substr($dir, strlen('all/' . Auth::user()->email . '/'));
                            } else {

                                $id = substr($dir, strlen('all/demo/'));
                            }


                            echo "<div class = 'col12  pdf' >";
                            echo "<a href='/pathviewViewer?id=$id&image=$v' target='_blank'><img class='pdf-info' width='500px' height='500px' src=" . $dir . $v . "></a>";
                            echo "<p class='pdf-info' style='align:center;'>" . $v . "</p>";
                            echo "</div>";
                        }

                    }
                    echo "</div>";
                    echo "</div>";

                    echo "</div>";
                    echo "</div>";
                    } else {
                        echo "Unable to generate pathway List";
                    }
                    ?>
                </div>


            </div>
            <script>
                $(document).ready(function () {


                    $("#expand").click(function () {
                        $('.expandable').toggle();


                        if ($("#expand").hasClass("glyphicon-triangle-bottom")) {
                            $("#expand").addClass('glyphicon-triangle-top').removeClass('glyphicon-triangle-bottom');
                        }
                        else {
                            $("#expand").addClass('glyphicon-triangle-bottom').removeClass('glyphicon-triangle-top');
                        }
                    });
                    $("#sideexpand").click(function () {
                        $('#colshow').toggle();
                        $('.side-expand').toggle();
                        if ($("#sideexpand").hasClass("glyphicon-forward")) {
                            $("#sideexpand").addClass('glyphicon-backward').removeClass('glyphicon-forward');
                        }
                        else {
                            $("#sideexpand").addClass('glyphicon-forward').removeClass('glyphicon-backward');
                        }
                    });
                });
            </script>
@stop
