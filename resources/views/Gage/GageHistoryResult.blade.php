@extends('GageApp')


@section('content')

    <div class="col-md-12">
        <div class="col-md-12">
            <div class="placeholders ">
                <h1> Gage Analysis Results </h1>

            </div>
            <?php

            echo public_path()."/all/".Auth::user()->email."/".$_GET['analyses'];
            $destDir = public_path()."/all/".Auth::user()->email."/".$_GET['analyses']."/";
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
                    if(strcmp($v,"gage.res.sig.txt") == 0)
                    {
                        echo "<h3> Selected pathways/gene sets:</h3>";
                        $sigLines = file($destDir .$v);
                        echo "<button type='button' id='expand' style='alignment: right;' > <span class='glyphicon glyphicon-triangle-bottom'></span> </button>";
                        echo "<table style='font-size: 14px;margin-bottom: 30px;width=100%' border=1>";
                        echo "<tbody>";
                        $lineNumer = 0;
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
                                    echo "<td>".$string."</td>";

                                    if($i==5)
                                        break;


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
                                    echo "<th>".$string."</th>";

                                    if($i==4)
                                        break;


                                }

                                echo "</tr>";
                            }
                            $lineNumer ++;
                        }
                        echo "</tbody>";
                        echo "</table>";

                    }
                }
                echo "<h3 >Example graphs for top gene sets</h3>";
                ?>
                <div class = 'col-md-12'>
                    <div class = 'col-md-6 pdf' >
                        <h3>Heatmaps</h3>
                        <?php
                        foreach($contents as $k => $v)
                        {

                            if(strpos($v,'geneData.heatmap.pdf'))
                            {

                                echo "<div class = 'col-md-12  pdf' >";
                                echo "<embed width='100%' height='600px' src=".$dir .$v.">";
                                echo "<p class='pdf-info'>".$v."</p>";
                                echo "</div>";
                            }

                        }
                        ?>
                    </div>
                    <div class = 'col-md-6 pdf' >
                        <h3>Scatter Plots</h3>
                        <?php
                        foreach($contents as $k => $v)
                        {

                            if(strpos($v,'geneData.pdf'))
                            {
                                echo "<div class = 'col-md-12 pdf'>";
                                echo "<embed width='100%' height='600px' src=".$dir .$v.">";
                                echo "<p class='pdf-info'>".$v."</p>";
                                echo "</div>";
                            }

                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12" style="font-size: 14px;">
            <div class="col-md-4">
                <?php

                $analyses =  DB::table('analyses')->where('analysis_id', $_GET['analyses'])->first();
                if(sizeof($analyses)>0)
                    {
                $argument = $analyses->arguments;

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
                echo "</tbody></table>";
                }?>
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
                ?>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {

            $('.expandable').toggle();
            $("#expand").click(function () {
                $('.expandable').toggle();
                $(this).text(function(i, text){
                    return text === "Expand" ? "Minimize" : "Expand";
                })
            });
        });
    </script>
@stop