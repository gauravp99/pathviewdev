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
                <h1> GAGE Analysis Results </h1>

            </div>
            <?php

            $db = new \App\Http\Models\DbDataFetch();
            $analysis_id = $_GET['analysis_id'];
            $isGageAnalysis = $_GET['discrete'];

            $argument = $db->getArgumentsList($analysis_id);

            $significant_flag = false;
            $gageresFileExist_flag = false;
            $pathview_flag = false;
            $refSampleNull_flag = false;
            //split
            $args = array();
            $args = explode(';', $argument);

            foreach ($args as &$arg) {
                $keyAndValue = explode(':', $arg);
                if (sizeof($keyAndValue) == 2) {

                    if ($keyAndValue[0] == 'destDir' || $keyAndValue[0] == 'destFile') {

                        if ($keyAndValue[0] == 'destDir') {
                            $destDir = $keyAndValue[1];
                            break;
                        }
                    }
                    if ($keyAndValue[0] == 'reference' || $keyAndValue[0] == 'sample') {
                        if (strcmp($keyAndValue[1], 'NULL') == 0) {
                            $refSampleNull_flag = true;
                        }
                    }


                }

            }

            $dir = substr($destDir, strlen(public_path()));
            $contents = scandir($destDir);

            if ($contents) {
                foreach ($contents as $key => $value) {
                    if ($value == "." || $value == "..") {
                        unset($key);
                    }
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
            ?>


            <div class="col12">
                <?php
                echo "<h1>Analysis : " . $analysis_id . "</h1>";
                echo "<h2 class='alert alert-info'> Click here to download the zipped output files <a href=" . $dir . "/file.zip target='_blank'>File.zip</a></h2>";



                foreach ($contents as $k => $v) {
                    if (strpos($v, "res.txt") > -1) {
                        $gageresFileExist_flag = true;
                    }
                    if (strpos($v, 'pathview') > -1) {
                        $pathview_flag = true;
                    }

                    if (strpos($v, "sig.txt") > -1) {
                        $sigLines = file($destDir . $v);
                        $significant_flag = true;
                        echo "<h2 class='section'> Selected pathways/gene sets:</h2>";

                        echo "<a href='#'><span type='button' id='sideexpand' style='alignment: left;margin-left: 1.5%;font-size: 30px;' class='glyphicon glyphicon-forward' ></span></a>";
                        echo "<p id='colshow' hidden='' style='margin-left: 65%;'><a href =$dir/$v target ='_blank'>Click here for full table</a>  </p>";


                        echo "<div class='col12'>";
                        echo "<div style='width:0.1%'>";
                        echo "<a href='#'><span  class = 'glyphicon glyphicon-triangle-bottom' id='expand' style='margin-left:-40px;alignment: right;font-size: 30px;' ></span></a>";
                        echo "</div >";
                        echo "<div style='width:99%'>";
                        $pathwaySet = array();
                        if(strcmp($isGageAnalysis,"true")==0)
                        {
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
                                            echo ""  . sprintf('%.2E', $temp) .  "";
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
                            echo "<h4> <a href =$dir/$v target ='_blank'>Click here for full table</a> </h4>";
                            echo "</div>";
                            echo "</div>";
                            foreach ($contents as $k => $v) {
                                if (strpos($v, ".png") > -1) {
                                    echo "<h2>Pathview Graphs:</h2>";
                                   break;
                                }
                            }

                            foreach ($contents as $k => $v) {

                                if (strpos($v, ".png") > -1) {
                                    if (Auth::user()) {
                                        $id = substr($dir, strlen('all/' . Auth::user()->email . '/'));
                                    } else {

                                        $id = substr($dir, strlen('all/demo/'));
                                    }


                                    echo "<div class = 'col12  pdf' >";
                                    //echo "<a href='/pathviewViewer?id=$id&image=$v' target='_blank'><img class='pdf-info' width='500px' height='500px' src=" . $dir . $v . "></a>";

                                    if (Auth::user()) {
                                        $analysisid = substr($dir, strlen('all/' . Auth::user()->email . '/') + 1, strlen($dir) - 2);
                                    } else {

                                        $analysisid = substr($dir, strlen('all/demo/') + 1, strlen($dir) - 2);
                                    }

                                    $startPositon = strpos($v, '.pathview');
                                    $speciesPathwayID = substr($v, 0, $startPositon);
                                    $analysisid = str_replace("/", "", $analysisid);

                                    echo "<a href='/viewer?analyses=$analysisid&id=$speciesPathwayID&image=$v' target='_blank'><img class='pdf-info' width='500px' height='500px' src=" . $dir . $v . "></a>";
                                    echo "<p class='pdf-info' style='align:center;'>" . $v . "</p>";
                                    echo "</div>";
                                }

                            }

                        }
                        else{
                            echo "<table style='font-size: 14px;margin-bottom: 30px;margin-top:-30px;width=100%;text-align: left;' border=1>";

                        echo "<tbody>";
                        $lineNumer = 0;

                        $pathwaySetName = array();
                        foreach ($sigLines as $temp) {
                            if ($lineNumer != 0) {
                                if ($lineNumer < 10)
                                    echo "<tr>";
                                else {
                                    echo "<tr class='expandable'>";
                                }
                                $temp = str_replace(array('"', "'"), '', $temp);
                                $array_string = explode("\t", $temp);
                                $i = 0;

                                foreach ($array_string as $string) {
                                    $i++;
                                    $float = (double)$string;
                                    if ($i == 1 && $lineNumer < 4) {
                                        $pathway_string = explode(" ", $string);
                                        array_push($pathwaySetName, $string);
                                        if (strpos($pathway_string[0], 'GO') !== false) {
                                            array_push($pathwaySet, str_replace(':', '_', $pathway_string[0]));
                                        } else {
                                            array_push($pathwaySet, $pathway_string[0]);
                                        }
                                    }



                                        if ($i > 6) {
                                            $float = (double)$string;
                                            echo "<td class='side-expand'>" . number_format((float)$float, 2, '.', '') . "</td>";
                                        } else {
                                            if ($i == 1 || $i == 6)
                                                echo "<td>" . $string . "</td>";
                                            else if ($i == 2 || $i == 4 || $i == 5) {

                                                echo "<td>" . sprintf('%.2E', $float) . "</td>";
                                            } else {

                                                echo "<td>" . number_format((float)$float, 2, '.', '') . "</td>";
                                            }

                                        }



                                }

                                echo "</tr>";
                            } else {
                                echo "<tr>";
                                $temp = str_replace(array('"', "'"), '', $temp);
                                $array_string = explode("\t", $temp);
                                $i = 0;
                                echo "<th>Pathway's</th>";
                                foreach ($array_string as $string) {
                                    $i++;


                                    if ($i > 5)
                                        echo "<th class='side-expand'>" . $string . "</th>";
                                    else {
                                        echo "<th>" . $string . "</th>";
                                    }

                                }

                                echo "</tr>";
                            }
                            $lineNumer++;
                            if ($lineNumer > 20) {
                                break;
                            }
                        }
                        echo "</tbody>";
                        echo "</table>";

                        echo "<h4> <a href =$dir/$v target ='_blank'>Click here for full table</a> </h4>";
                        echo "</div>";
                        echo "</div>";
                        }
                    }
                }
                if (!$significant_flag && $gageresFileExist_flag) {
                    $errorFlag = false;
                    $lines = file($destDir . "/errorFile.Rout");
                    foreach ($lines as $temp) {
                        $temp = strtolower($temp);
                        $pos = strpos($temp, 'error');
                        if ($pos !== false) {

                            $errorFlag = true;
                            if (strpos($temp, 'testforvalidkeys(x, keys, keytype)')) {
                                echo "<h3 class='alert alert-danger'>None of values in Input Data and Specified Gene ID Type is matching</h3>";
                            } else
                                echo "<h3 class='alert alert-danger'>$temp</h3>";

                        }
                    }
                    if (!$errorFlag)
                        echo "<h3 class='alert alert-danger'> No gene sets are significant, you may relax your selection criteria (Cutoff value).</h3>";
                    foreach ($contents as $k1 => $v1) {
                        if (strpos($v1, "res.txt") > -1) {
                            $sigLines = file($destDir . $v1);
                            echo "<h2 class='section'> Complete pathways/gene sets:</h2>";

                            echo "<a href='#'><span type='button' id='sideexpand' style='alignment: left;margin-left: 1.5%;font-size: 30px;' class='glyphicon glyphicon-forward' ></span></a>";
                            echo "<div class='col12'>";
                            echo "<div style='width:0.1%'>";
                            echo "<a href='#'><span  class = 'glyphicon glyphicon-triangle-bottom' id='expand' style='margin-left:-40px;alignment: right;font-size: 30px;' ></span></a>";
                            echo "</div>";
                            echo "<div style='width:99%'>";
                            if(strcmp($isGageAnalysis,"true")==0)
                            {
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
                                                    echo ""  . sprintf('%.2E', $temp) .  "";
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


                            }
                            else{
                                echo "<table style='font-size: 14px;margin-bottom: 30px;width=100%;text-align: left;' border=1>";
                                echo "<tbody>";
                                $lineNumer = 0;
                            foreach ($sigLines as $temp) {
                                if ($lineNumer != 0) {
                                    if ($lineNumer < 10)
                                        echo "<tr>";
                                    else {
                                        echo "<tr class='expandable'>";
                                    }
                                    $temp = str_replace(array('"', "'"), '', $temp);
                                    $array_string = explode("\t", $temp);
                                    $i = 0;

                                    foreach ($array_string as $string) {
                                        $i++;
                                        $float = (double)$string;
                                        if ($i == 1 && $lineNumer < 4) {
                                            $pathway_string = explode(" ", $string);
                                            array_push($pathwaySetName, $string);
                                            if (strpos($pathway_string[0], 'GO') !== false) {
                                                array_push($pathwaySet, str_replace(':', '_', $pathway_string[0]));
                                            } else if($pathway_string[0]) {
                                                array_push($pathwaySet, $pathway_string[0]);
                                            }
                                        }

                                        if ($i > 6) {
                                            if ($i > 18) {
                                                break;
                                            }
                                            $float = (double)$string;
                                            echo "<td class='side-expand'>" . number_format((float)$float, 2, '.', '') . "</td>";
                                        } else {
                                            if ($i == 1 || $i == 6)
                                                echo "<td>" . $string . "</td>";
                                            else if ($i == 2 || $i == 4 || $i == 5) {

                                                echo "<td>" . sprintf('%.2E', $float) . "</td>";
                                            } else {

                                                echo "<td>" . number_format((float)$float, 2, '.', '') . "</td>";
                                            }

                                        }

                                    }

                                    echo "</tr>";
                                } else {
                                    echo "<tr>";
                                    $temp = str_replace(array('"', "'"), '', $temp);
                                    $array_string = explode("\t", $temp);
                                    $i = 0;
                                    echo "<th>Pathway's</th>";
                                    foreach ($array_string as $string) {
                                        $i++;


                                        if ($i > 5)
                                            echo "<th class='side-expand'>" . $string . "</th>";
                                        else {
                                            echo "<th>" . $string . "</th>";
                                        }

                                    }

                                    echo "</tr>";
                                }
                                $lineNumer++;
                                if ($lineNumer > 20) {
                                    break;
                                }
                            }



                            }
                            echo "</tbody>";
                            echo "</table>";
                            echo "</div>";
                            echo "</div>";
                            echo "<h4> <a href ='$dir/$v' target ='_blank'>Click here for full table</a> </h4>";


                        }
                    }
                }

                if($significant_flag)
                {
                if(sizeof($pathwaySet) > 0)
                {
                $i = 0;
                echo "<h2 class='section'>Example graphs for top gene sets:</h2>";
                echo "<div class = 'col12'>";
                echo "<div class = 'col8'>";
                foreach($pathwaySet as $pathway)
                {
                $i++;
                ?>


                <div class='col6 pdf'>
                    <?php
                    if($i == 1)
                    {
                    if($pathview_flag)
                    {
                    ?>
                    <h3 class="section-header">Pathview graphs</h3>
                    <?php
                    }else{
                    if($refSampleNull_flag && $i == 1)
                    {
                        echo "<div class = 'col12 pdf'>";
                        echo "<h3 class='alert alert-info'> Heatmaps and Scatter plots cannot be generated as given NULL value for reference and sample columns</h3>";
                        echo "</div>";
                    }
                    else{
                    ?>
                    <h3 class="section-header">Heatmaps</h3>
                    <?php
                    }}}
                    $numOfFile = 1;
                        $crtd_pathview = false;
                    foreach ($contents as $k => $v) {
                        if ($pathview_flag && (strpos($v, $pathway) > -1 && strpos($v, 'pathview') > -1 && strpos($v, 'png') > -1)) {
                            $numOfFile++;
                            $crtd_pathview = true;

                            echo "<div class = 'col12  pdf' >";
                            if (Auth::user()) {
                                $analysisid = substr($dir, strlen('all/' . Auth::user()->email . '/') + 1, strlen($dir) - 2);
                            } else {

                                $analysisid = substr($dir, strlen('all/demo/') + 1, strlen($dir) - 2);
                            }

                            $startPositon = strpos($v, '.pathview');
                            $speciesPathwayID = substr($v, 0, $startPositon);
                            $analysisid = str_replace("/", "", $analysisid);
                            echo "<a href='/viewer?analyses=$analysisid&id=$speciesPathwayID&image=$v' target='_blank'><img class='pdf-info' width='500px' height='500px' src=" . $dir . $v . "></a>";
                            echo "<p class='pdf-info' style='align:center;'>" . $pathwaySetName[$i - 1] . "</p>";
                            echo "</div>";

                            if ($numOfFile >= 3) {
                                break;
                            }

                        } else if ($pathview_flag && (strpos($v, $pathway) > -1 && strpos($v, 'pdf') > -1 && strpos($v, 'pathview') > -1)) {
                            $numOfFile++;
                            $crtd_pathview = true;
                            echo "<div class = 'col12  pdf' >";
                            echo "<embed width='500px' height='500px' src=" . $dir . $v . ">";
                            echo "<p class='pdf-info' style='align:center;'>" . $pathwaySetName[$i - 1] . "</p>";
                            echo "</div>";
                            if ($numOfFile >= 3) {
                                break;
                            }


                        } else if (!$pathview_flag && strcmp($v, $pathway . '.geneData.heatmap.pdf') == 0) {
                            $numOfFile++;
                            echo "<div class = 'col12  pdf' >";
                            echo "<embed width='500px' height='500px' src=" . $dir . $v . ">";
                            echo "<p class='pdf-info'>" . $pathwaySetName[$i - 1] . "</p>";
                            echo "</div>";
                            if ($numOfFile >= 3) {
                                break;
                            }
                        }

                    }

                        if(!$crtd_pathview && $pathview_flag){
                            echo "<div class = 'col12  pdf' >";

                            echo "<p class='pdf-info'>Reminder: This graphs not generated due to no/low connection</p>";
                            echo "</div>";
                        }
                    ?>
                </div>
                <div class='col6 pdf'>
                    <?php

                    if($refSampleNull_flag && $i == 1)
                    {

                    }else{
                    if($i == 1)
                    {
                    ?>
                    <h3 class="section-header">Scatter Plots</h3>
                    <?php
                    }
                    $numOfFile = 1;
                    foreach ($contents as $k => $v) {

                        if (strcmp($v, $pathway . '.geneData.pdf') == 0) {
                            $numOfFile++;

                            echo "<div class = 'col12 pdf'>";
                            echo "<embed width='500px' height='500px' src=" . $dir . $v . ">";
                            echo "<p class='pdf-info'>" . $pathwaySetName[$i - 1] . "</p>";
                            echo "</div>";
                            if ($numOfFile >= 3) {
                                break;
                            }
                        }

                    }
                    }
                    ?>
                </div>


                <?php

                }
                ?>


                <h3 class="section-header">Combined GeneSet HeatMap</h3>

                <?php

                foreach ($contents as $k => $v) {

                    if (strcmp($v, 'gage.res.gs.heatmap.pdf') == 0) {
                        echo "<div class = 'col12 pdf'>";
                        echo "<div class = 'col6 pdf' >";
                        echo "<div class = 'col12 pdf' >";
                        echo "<embed width='500px' height='500px' src=" . $dir . $v . "#page=1>";
                        echo "<p class='pdf-info'>$v -page 1</p>";
                        echo "</div>";
                        echo "</div>";
                        echo "<div class = 'col6 pdf' >";
                        echo "<div class = 'col12 pdf' >";
                        echo "<embed width='500px' height='500px' src=" . $dir . $v . "#page=2>";
                        echo "<p class='pdf-info'>$v -page 2</p>";
                        echo "</div>";
                        echo "</div>";
                        echo "<div class = 'col6 pdf' >";
                        echo "<div class = 'col12 pdf' >";
                        echo "<embed width='500px' height='500px' src=" . $dir . $v . "#page=3>";
                        echo "<p class='pdf-info'>$v -page 3</p>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";

                    }

                }


                ?>
            </div>
        </div>
    </div>
    <?php
    }
    }
    ?>



    <div class="col12" style="font-size: 14px;">

        <div class="col-md-4">
            <h3>Files Generated</h3>
            <?php
            $count_files = 0;
            foreach ($contents as $k => $v) {


                if (strcmp($v, '.') == 0 || strcmp($v, '..') == 0 || strcmp($v, 'errorFile.Rout') == 0 || strcmp($v, 'outputFile.Rout') == 0 || strcmp($v, 'workenv.RData') == 0) {

                } else {
                    if ($count_files > 26) {
                        echo "<a href ='/fullList?id=$direc'>Click here</a> for full list of the files generated";
                        break;
                    }
                    $count_files++;


                    if (strpos($v, "pathview") > -1 && strpos($v, ".png") > -1) {
                        if (Auth::user()) {
                            $analysisid = substr($dir, strlen('all/' . Auth::user()->email . '/') + 1, strlen($dir) - 2);
                        } else {

                            $analysisid = substr($dir, strlen('all/demo/') + 1, strlen($dir) - 2);

                        }
                        str_replace("/", "", $analysisid);
                        $startPositon = strpos($v, '.pathview');
                        $speciesPathwayID = substr($v, 0, $startPositon);
                        $analysisid = str_replace("/", "", $analysisid);
                        echo "<li><a href='/viewer?analyses=$analysisid&id=$speciesPathwayID&image=$v' target='_blank'>$v</a></li>";


                    } else {
                        echo "<li><a target=\"_blank\" href=\"$dir/" . $v . "  \">$v</a></li>";
                    }

                }

            }


            ?>
        </div>
        <div class="col-md-4">
            <?php

            //split
            $args = array();
            $args = explode(';', $argument);
            echo "<h3>User Input Values</h3>";
            echo "<table border=1><tbody><tr><th>Argument</th><th>Argument Value</th>";
            foreach ($args as &$arg) {
                $keyAndValue = explode(':', $arg);
                if (sizeof($keyAndValue) == 2) {

                    if ($keyAndValue[0] == 'destDir' || $keyAndValue[0] == 'destFile') {
                        continue;
                    } else {
                        echo "<tr><td>" . $keyAndValue[0] . "</td><td>" . $keyAndValue[1] . "</td></tr>";
                    }

                }

            }
            echo "</tbody></table>";?>
        </div>
        <div class='col-md-4'>
            <div><h3> Output/Error Log </h3>
                <?php

                $lines = file($destDir . "/errorFile.Rout");
                $flag = false;
                ?>
                <ul class="nav navbar-nav" style="margin-left: 30px;">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle list-group-item active" style="width:100%;"
                           data-toggle="dropdown">Click Here Analysis Logs</a>
                        <ul class="dropdown-menu" style="width:100%;">
                            <?php
                            $count_lines = 0;
                            $numberOfLines = sizeof($lines);


                                if($numberOfLines > 50)
                                    {
                                        foreach ($lines as $temp) {

                                            $count_lines++;
                                            if($numberOfLines - $count_lines <=50)
                                                {
                                                    if (strpos($temp, 'directory') == false) {

                                                        echo "<div style='width:100%;'>" . $temp . "</div>";
                                                    }
                                                }

                                        }

                                    }else{
                                    foreach ($lines as $temp) {
                                        $count_lines++;
                                        if (strpos($temp, 'directory') == false) {
                                            echo "<div style='width:100%;'>" . $temp . "</div>";
                                        }
                                }


                            }
                            ?>
                        </ul>
                    </li>
                </ul>
                <?php

                echo "</div>";

                /* }*/
                ?>
            </div>

        </div>

        <div class="scroll">
            <a href="#" class="scrollToTop"><span class="glyphicon glyphicon-menu-up"
                                                  style="font-size: 30px; margin-left: 100px;"></span></a></div>
        <style>
            .scrollToTop {
                width: 100px;
                height: 130px;
                padding: 10px;
                text-align: center;
                background: whiteSmoke;
                font-weight: bold;
                color: #444;
                text-decoration: none;
                position: fixed;
                top: 75px;
                right: 40px;
                display: none;
                font-size: 50px;

                background: url("/images/icontop.png") no-repeat 0px 20px;
            }

            .scrollToTop:hover {
                text-decoration: none;
            }
        </style>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <script>
            $(document).ready(function () {

                //Check to see if the window is top if not then display button
                $(window).scroll(function () {
                    if ($(this).scrollTop() > 100) {
                        $('.scrollToTop').fadeIn();
                    } else {
                        $('.scrollToTop').fadeOut();
                    }
                });

                //Click event to scroll to top
                $('.scrollToTop').click(function () {
                    $('html, body').animate({scrollTop: 0}, 800);
                    return false;
                });

            });
        </script>
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
