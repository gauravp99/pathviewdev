@extends('app')
@section('content')
    @include('navigation')

    <div class="col-md-10">
        <div class="col-md-8">
            <?php

            $exception = Session::get('exception', '');
            if (Auth::user()) {
                $directory = public_path() . "/all/" . Auth::user()->email . "/" . $_GET['analyses'];
                $directory1 = "/all/" . Auth::user()->email . "/" . $_GET['analyses'];

            } else {
                $directory = public_path() . "/all/demo/" . $_GET['analyses'];
                $directory1 = "/all/demo/" . $_GET['analyses'];
            }

            $contents = scandir($directory);

            if ($contents) {
                foreach ($contents as $key => $value) {
                    if ($value == "." || $value == "..") {
                        unset($key);
                    }
                }
            }

            $val = "";
            $id = $_GET['id'];
            $id = substr($id, 0, 3);
            $flag = false;
            $flag1 = false;
            $flag2 = false;
            $files_to_zip = array();
            $suffix = $_GET['suffix'];
            $analyses_id = $_GET['analyses'];

                //print pathway generated text files with hiperlink to the files
            foreach ($contents as $k => $v) {

                if (strpos($v, 'log') !== false || strpos($v, "" . $id . ".txt")) {
                    echo "<li><a target=\"_blank\" href=\"$directory1/" . $v . "  \">$v</a></li>";
                    array_push($files_to_zip, $directory1 . "/" . $v);
                }
            }


            if(!$flag1)
            {
            echo "<h2>Pathview Graphs:</h2>";
            echo "<ul >";
            foreach ($contents as $k => $v) {

            //echo $k;
            if (strpos($v, $_GET['suffix'])) {
            $val = $v;
            $analyses_id = $_GET['analyses'];
            $id = substr($val, 0, 8);
                //print hyperlink for the images/PDF generated
            if (strpos($val, 'png')) {
            echo "<li  style='font-size: 24px; list-style-type: none;'>  <a target=\"_blank\" href=\"viewer?analyses=$analyses_id&id=$id&image=$val\" > " . $val . "</a></li>";?>

            <?php

            array_push($files_to_zip, $directory1 . "/" . $val);

            } else {
                echo "<li  style='font-size: 24px;list-style-type: none;'>  <a target=\"_blank\" href=\"$directory1/" . $val . "  \">$val</a></li>";
                array_push($files_to_zip, $directory1 . "/" . $val);
            }

            }

            }

            echo "</ul>";

            //create a zip file so that it can be used to download
            chdir($directory);
            $rootPath = realpath($directory);

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
                    if (strpos($file, 'log') !== false || strpos($file, "" . $id . ".txt") || (strpos($file, $suffix))) {
                        // Get real and relative path for current file
                        $filePath = $file->getRealPath();
                        $relativePath = substr($filePath, strlen($rootPath) + 1);

                        // Add current file to archive
                        $zip->addFile($filePath, $relativePath);
                    }
                }
            }

            // Zip archive will be created only after closing object
            $zip->close();

            echo "<h2 class='alert alert-info' style='font-size: 18px;color:black;'>Click <a style='color:blue;'  href=\"" . $directory1 . "/file.zip\">here</a> to download zipped output files. </h2>";
            }
                    //Printing specific warning and errors on the analysis
            foreach ($contents as $k => $v) {
            if(strcmp($v, "errorFile.Rout") == 0)
            {

            $v = $directory . "/" . $v;
            if(filesize($v) > 0)
            {

            $lines = file($v);
            foreach ($lines as $temp) {
                $temp = strtolower($temp);
                $array_string = explode(" ", $temp);
                foreach ($array_string as $a_string) {
                    if (strcmp($a_string, 'error') == 0 || strcmp($a_string, 'warning:') == 0 || strcmp($a_string, 'error:') == 0) {

                        if (!$flag1) {


                            echo "<h4 class='alert alert-warning'> Species and pathway id combination is not Valid/present at KEGG";
                            echo " Or make sure your input gene and compound uploaded data is in the requested format</h4>";
                            echo "<br/><h4 class='alert alert-warning'>Mail Send to the admin and admin will reply to you as early as possible with solution</h4><ul class='alert alert-danger'>";
                            if (strcmp($a_string, 'error') == 0 || strcmp($a_string, 'warning:') == 0) {
                                $flag1 = true;
                            }

                        }


                        echo "<li>" . $temp . "</li>";

                        $flag = true;

                    }

                }
            }
            if ($flag1) {
                echo "</ul>";
                echo "<button onclick='goBack()'>Go Back</button>";
            }
            ?>

            <script>
                function goBack() {
                    window.history.back();
                }
            </script><?php
            }
            }
            }
            ?>
            <!-- Printing erros and log to the drop down menu so that user can view the logs -->
        </div>
        <div class="list-group col-md-4">
            <ul class="nav navbar-nav" style="margin-left: 30px;">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle list-group-item active" style="width:100%;"
                       data-toggle="dropdown">Click here to see Analysis Logs</a>
                    <ul class="dropdown-menu" style="width:100%;">

                        <?php
                        if(file_exists($directory . "/errorFile.Rout"))
                            {
                                $lines = file($directory . "/errorFile.Rout");
                                $flag = false;

                                foreach ($lines as $temp) {
                                    if (strpos($temp, 'directory') == false) {

                                        echo "<div style='width:100%;'>" . $temp . "</div>";
                                    }
                                }



                            }
                        ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>



@stop