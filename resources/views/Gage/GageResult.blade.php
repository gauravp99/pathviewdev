@extends('GageApp')


@section('content')

    <div class="col-md-12">
        <div class="col-md-12">
            <div class="placeholders ">
                <h1> GAGE Analysis Results </h1>

                </div>
<?php

                $argument = $_SESSION['argument'];
          /*  $analysis_id = $_GET['analysis_id'];
                $email ="";
                if(Auth::user())
                    {
                        $id = Auth::user()->id;
                    }
                else{
                    $id = 0;
                }
                $analysis = DB::table('analyses')->where('analysis_id',$analysis_id)->first();
                if(sizeof($analysis) > 0 )
                    {
                $argument = $analysis->arguments;*/
            $significant_flag = false;
            $gageresFileExist_flag = false;
            $pathview_flag = false;
            $refSampleNull_flag = false;
            //split
                $args = array();
            $args = explode(';',$argument);

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
                                            break;
                                            }
                                }
                            if($keyAndValue[0] == 'reference' || $keyAndValue[0] == 'sample' )
                                {
                                    if( strcmp($keyAndValue[1],'NULL') == 0)
                                        {
                                            $refSampleNull_flag = true;
                                        }
                                }


                            }

                }

            $dir = substr($destDir,strlen("/var/www/Pathway/public/"));
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
                    if(!strpos($filePath,'RData')&& !strpos($filePath,'Rout'))
                    $zip->addFile($filePath, $relativePath);

                }
            }

            // Zip archive will be created only after closing object
            $zip->close();
            ?>


        <div class="col-md-12">
            <?php

echo "<h2 class='alert alert-info'> Click here to download the zipped output files <a href=".$dir."/file.zip target='_blank'>File.zip</a></h2>";



            foreach($contents as $k => $v)
            {
                if(strcmp($v,"gage.res.txt"))
                    {
                        $gageresFileExist_flag = true;
                    }
                if(strpos($v,'pathview'))
                    {
                        $pathview_flag = true;
                    }
            if(strcmp($v,"gage.res.sig.txt") == 0)
            {
                $sigLines = file($destDir .$v);
                        $significant_flag = true;
                echo "<h2 class='section'> Selected pathways/gene sets:</h2>";

                echo "<a href='#'><span type='button' id='sideexpand' style='alignment: left;margin-left: 1.5%;font-size: 30px;' class='glyphicon glyphicon-forward' ></span></a>";
                echo "<div class='col-md-12'>";
                echo "<div style='width:0.1%'>";
                echo "<a href='#'><span  class = 'glyphicon glyphicon-triangle-bottom' id='expand' style='margin-left:-40px;alignment: right;font-size: 30px;' ></span></a>";
                echo "</div >";
                echo "<div style='width:99%'>";
                echo "<table style='font-size: 14px;margin-bottom: 30px;margin-top:-30px;width=100%;text-align: left;' border=1>";
                echo "<tbody>";
                $lineNumer = 0;
                $pathwaySet =array();
                $pathwaySetName =array();
                foreach ($sigLines as $temp) {
                    if($lineNumer !=0)
                    {
                        if($lineNumer< 10 )
                        echo "<tr>";
                        else {
                            echo "<tr class='expandable'>";
                        }
                        $temp = str_replace(array('"',"'"),'',$temp);
                        $array_string = explode("\t", $temp);
                        $i =0;

                        foreach ($array_string as $string)
                        {
                            $i++;
                            $float = (double)$string;
                            if($i == 1 && $lineNumer < 4)
                                {
                                    $pathway_string = explode(" ", $string);
                                    array_push($pathwaySetName,$string);
                                    if(strpos($pathway_string[0],'GO') !== false)
                                        {
                                            array_push($pathwaySet,str_replace(':','_',$pathway_string[0]));
                                        }
                                    else{
                                        array_push($pathwaySet,$pathway_string[0]);
                                    }
                                }

                            if($i>6)
                                {
                                    $float = (double)$string;
                                    echo "<td class='side-expand'>".number_format((float)$float, 2, '.', '')."</td>";
                                }

                            else{
                                if($i==1 || $i ==6)
                                    echo "<td>".$string."</td>";
                                else if($i==2 || $i ==4 || $i==5)
                                {

                                    echo "<td>".sprintf('%.2E', $float)."</td>";
                                }
                                else
                                {

                                    echo "<td>".number_format((float)$float, 2, '.', '')."</td>";
                                }

                            }

                        }

                        echo "</tr>";
                    }
                    else{
                        echo "<tr>";
                        $temp = str_replace(array('"',"'"),'',$temp);
                        $array_string = explode("\t", $temp);
                        $i =0;
                        echo "<th>Pathway's</th>";
                        foreach ($array_string as $string)
                        {
                            $i++;


                            if($i>5)
                                echo "<th class='side-expand'>".$string."</th>";
                            else {
                                echo "<th>".$string."</th>";
                            }

                        }

                        echo "</tr>";
                    }
                    $lineNumer ++;
                }
                echo "</tbody>";
                echo "</table>";
                echo "</div>";
echo "</div>";
            }
            }
                if(!$significant_flag && $gageresFileExist_flag)
                {
                    echo "<h3 class='alert alert-danger'> No gene sets are significant, you may relax your selection criteria (Cutoff value).</h3>";
                    foreach($contents as $k1 => $v1)
                    {
                        if(strcmp($v1,"gage.res.txt") == 0)
                        {
                            $Lines = file($destDir ."gage.res.txt");
                            echo "<h2 class='section'> Complete pathways/gene sets:</h2>";

                            echo "<a href='#'><span type='button' id='sideexpand' style='alignment: left;' class='glyphicon glyphicon-menu-right' ></span></a>";
                            echo "<table style='font-size: 14px;margin-bottom: 30px;width=100%;text-align: left;' border=1>";
                            echo "<tbody>";
                            $lineNumer = 0;
                            foreach ($Lines as $temp) {
                                if($lineNumer !=0)
                                {
                                    if($lineNumer< 10 )
                                        echo "<tr>";
                                    else {
                                        echo "<tr class='expandable'>";
                                    }
                                    $temp = str_replace(array('"',"'"),'',$temp);
                                    $array_string = explode("\t", $temp);
                                    $i =0;

                                    foreach ($array_string as $string)
                                    {
                                        $i++;


                                        $float = (double)$string;
                                        if($i>6 )
                                            {

                                                    $float = (double)$string;
                                                    echo "<td class='side-expand'>".number_format((float)$float, 2, '.', '')."</td>";


                                                }
                                        else{

                                            if($i==1 || $i ==6)
                                                echo "<td>".$string."</td>";
                                            else if($i==2 || $i ==4 || $i==5)
                                            {

                                                echo "<td>".sprintf('%.2E', $float)."</td>";
                                            }
                                            else
                                            {

                                                echo "<td>".number_format((float)$float, 2, '.', '')."</td>";
                                            }
                                        }

                                    }

                                    echo "</tr>";
                                }
                                else{
                                    echo "<tr>";
                                    $temp = str_replace(array('"',"'"),'',$temp);
                                    $array_string = explode("\t", $temp);
                                    $i =0;
                                    echo "<th>Pathway's</th>";
                                    foreach ($array_string as $string)
                                    {
                                        $i++;


                                        if($i>5 )
                                            echo "<th class='side-expand'>".$string."</th>";
                                        else {
                                            echo "<th>".$string."</th>";
                                        }

                                    }

                                    echo "</tr>";
                                }
                                $lineNumer ++;
                            }
                            echo "</tbody>";
                            echo "</table>";
                            echo "<a href='#'><span  class = 'glyphicon glyphicon-plus' id='expand' style='alignment: right;margin-top: -20px;' ></span></a>";
                        }
                    }
                }

                if($significant_flag)
                    {
                if(sizeof($pathwaySet)>0)
                {
                $i =0;
            echo "<h2 class='section'>Example graphs for top gene sets:</h2>";
                foreach($pathwaySet as $pathway)
                    {
                    $i++;






                ?>

                <div class = 'col-md-12'>

                <div class = 'col-md-6 pdf' >
                    <?php
                    if($i==1)
                    {
                        if($pathview_flag)
                        {
                            ?>
                        <h3 class="section-header">Pathview graphs</h3>
                        <?php
                        }else{
                    ?>
                    <h3 class="section-header">Heatmaps</h3>
                <?php
                        }}

            foreach($contents as $k => $v)
            {

                if($pathview_flag && (strcmp($v,$pathway.'.pathview.multi.png')==0 || strcmp($v,$pathway.'.pathview.png') ==0 ) )
                    {

                        echo "<div class = 'col-md-12  pdf' >";
                        if(Auth::user())
                            {
                        $id = substr($dir,strlen('all/'.Auth::user()->email.'/'));
                                }
                        else
                            {

                                $id = substr($dir,strlen('all/demo/'));
                            }
                        echo "<a href='/pathviewViewer?id=$id&image=$v' target='_blank'><img class='pdf-info' width='500px' height='500px' src=".$dir .$v."></a>";
                        echo "<p class='pdf-info' style='align:center;'>".$pathwaySetName[$i-1]."</p>";
                        echo "</div>";
                    }else if(!$pathview_flag && strcmp($v,$pathway.'.geneData.heatmap.pdf')==0 )
                    {

                    echo "<div class = 'col-md-12  pdf' >";
                    echo "<embed width='500px' height='500px' src=".$dir .$v.">";
                        echo "<p class='pdf-info'>".$pathwaySetName[$i-1]."</p>";
                    echo "</div>";
                    }

            }

            ?>
                    </div>
                <div class = 'col-md-6 pdf' >
                    <?php
                    if($i==1)
                    {
                    ?>
                    <h3 class="section-header">Scatter Plots</h3>
                    <?php
                    }
                            if($refSampleNull_flag && $i==1)
                                {
                                    echo "<div class = 'col-md-12 pdf'>";
                                    echo "<h3 class='alert alert-info'> Scatter plots cannot be generated as given NULL value for reference and sample columns</h3>";
                                    echo "</div>";
                                }else{


                    foreach($contents as $k => $v)
                    {

                        if(strcmp($v,$pathway.'.geneData.pdf')==0 )
                        {
                            echo "<div class = 'col-md-12 pdf'>";
                            echo "<embed width='500px' height='500px' src=".$dir .$v.">";
                            echo "<p class='pdf-info'>".$pathwaySetName[$i-1]."</p>";
                            echo "</div>";
                        }

                    }
                    }
                        ?>
                </div>
                </div>
            <?php

                }
                }
                }?>
        </div>
        </div>
        <div class="col-md-12" style="font-size: 14px;">
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
        <div class="col-md-4">
            <h3>Files Generated</h3>
        <?php

            foreach ($contents as $k => $v) {
                if(strcmp($v,'.')==0||strcmp($v,'..')==0||strcmp($v,'errorFile.Rout')==0||strcmp($v,'outputFile.Rout')==0||strcmp($v,'workenv.RData')==0)
                    {

                    }
                else
            echo "<li><a target=\"_blank\" href=\"$dir/" . $v . "  \">$v</a></li>";

            }

           echo "</div><div class='col-md-4'><div> <h3> Output/Error Log </h3>";

            $lines = file($destDir . "/errorFile.Rout");
            $flag = false;
?>
            <ul class="nav navbar-nav" style="margin-left: 30px;">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle list-group-item active" style="width:100%;"
                       data-toggle="dropdown">Click Here Analysis Logs</a>
                    <ul class="dropdown-menu" style="width:100%;">
            <?php
            foreach ($lines as $temp) {
                if (strpos($temp, 'directory') == false) {

                    echo "<div style='width:100%;'>" . $temp . "</div>";
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
    </div>
    <script>
        $(document).ready(function () {

            $('.expandable').toggle();
            $('.side-expand').toggle();
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