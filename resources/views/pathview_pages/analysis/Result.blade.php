@extends('app')


@section('content')

    <script>
        $("#waiting").hide();
        $("#completed").hide();
        $("#progress").hide();
        //ajax call to check if the analysis is done by the queue listener check is done on every 3 seconds once return true checking is closed
        var j =0;
        var increment = 10;
        var waitFlag = false;
        var waitFlag1 = false;
        $(document).ready(function () {
            if($('#status').val())
            {
                $("#waiting").remove();
                $("#progress").hide();
                $("#completed").show();
            }else {
                $('#status').val("visited");

                var myvar = "<?php echo $analysisid;?>";
                var id = "<?php echo $_SESSION['id']; ?>";
                var suffix = "<?php echo $_SESSION['suffix'];?>";
                var argument = "<?php echo $_SESSION['argument'];?>";
                var anal_type = "<?php echo $_SESSION['anal_type'];?>";
                var queue_id = "<?php echo $queueid;?>";
                var factor = "<?php echo $factor;?>";

                //console.log("currentFactor:"+(factor));
                console.log(queue_id);

                    waitFlag = true;

                if(factor != -1000)
                {
                    if (queue_id == -1) {
                        $("#progress").hide();
                        $("#completed").hide();
                        console.log("in waiting method");
                        $("#waiting").show();
                        console.log("watiting for other jobs to complete");
                        var myVar2 = setInterval(function () {
                            $.ajax({
                                url: "/ajax/waitingAnalysisStatus",
                                method: 'POST',
                                data: {
                                    'analysisid': myvar,
                                    'id': id,
                                    'suffix': suffix,
                                    'argument': argument,
                                    'anal_type': anal_type
                                },
                                success: function (data) {
                                    if (data.length > 0) {
                                        console.log("success: data " + data);

                                        if (data == "pushedJob") {

                                            $("#waiting").remove();
                                            $("#progress").show();
                                            waitFlag1 = true;
                                            clearInterval(myVar2);

                                        }
                                    }
                                },
                                error: function (data) {
                                    console.log("error" + data);
                                }
                            });
                        }, 1000);
                    }


                    if (queue_id > 0 || waitFlag) {

                        console.log("in progress method");
                        $("#completed").hide();
                        if(queue_id > 0 )
                            $("#waiting").remove();

                        if( waitFlag1 ||queue_id > 0)
                            $("#progress").show();
                        var myVar1 = setInterval(function () {
                            j = j + 1;
                            $.ajax({
                                url: "/ajax/analysisStatus",
                                method: 'POST',
                                data: {
                                    'analysisid': myvar,
                                    'id': id,
                                    'suffix': suffix,
                                    'argument': argument,
                                    'anal_type': anal_type
                                },
                                success: function (data) {
                                    if (data.length > 0) {
                                        console.log("success: data " + data);
                                        $("#waiting").remove();
                                        $("#progress").show();
                                        $("#completed").hide();
                                        if (data === "true") {
                                            $('#progressData').text("100%");
                                            $('#progressData').attr('aria-valuenow', '100');
                                            $("#progress").remove();
                                            $("#completed").show();
                                            clearInterval(myVar1);
                                        } else {

                                            var fac = Math.round((j * 10)/factor);
                                            if(fac < 1)
                                            {
                                                fac = 1;
                                            }
                                            if(fac > 100)
                                            {

                                                $('#progressData').css("opacity","0.4");
                                                $('#progressImage').show();
                                                $('#progressData').text("100%");
                                            }
                                            else{

                                                $('#progressData').text("" +fac + "%");
                                                $('#progressData').attr('aria-valuenow', "" + fac);
                                                $('#progressData').css('width', fac + '%');
                                            }

                                        }
                                    }
                                },
                                error: function (data) {
                                    console.log("error" + data);
                                }
                            });
                        }, 1500);

                    } else {

                        $('#completed').show();
                    }
                }else{
                    $("#waiting").remove();
                    $("#progress").remove();

                }

            }
        });


    </script>
    @include('navigation')

    <div class="col-md-10">
        <div class="col-md-8">
            <div class="placeholders ">
                <h1> Analysis Results </h1>

                <h2>Analysis ID:{{$_SESSION['analyses_id']}}</h2>
<?php
                    if(!Auth::user())
                        {
                            Session::put('anal_id',$_SESSION['analyses_id']);
                        }

                    ?>
                <h1> Analysis Input arguments </h1>
                <br/>

                <div class="bs-example" data-example-id="condensed-table">
                    <table border=1 class="table table-condensed" style="width:70%;text-align: left;">
                        <thead>
                        <tr>
                            <th>Argument Name</th>
                            <th>Value</th>

                            <tbody>


                            <?php
                            $arguments = array();
                            $species = "";
                            $suffix = "";
                            $arguments = explode(";", $_SESSION['argument']);
                            $arguments = array_unique($arguments);
                            foreach($arguments as $arg)
                            {
                            if(strpos($arg, ":"))
                            {

                            $arg1 = explode(':', $arg);
                            if(sizeof($arg1) > 1 && strcmp($arg1[0], "targedir") != 0 )
                            {
                            switch($arg1[0])
                            {
                            case "geneid":
                            $arg1[0] = "Gene ID Type";
                            ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;

                        case "cmpdid":
                        $arg1[0] = "Compound ID Type";
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;

                        case "species":
                        $arg1[0] = "Species";
                        $val = DB::select(DB::raw("select concat(concat(species_id,\"-\"),species_desc) as spe from species where species_id like '$arg1[1]' LIMIT 1"));

                        if (sizeof($val) > 0) {
                            $arg1[1] = $val[0]->spe;
                            $species = $val[0]->spe;
                        }
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;
                        case "suffix":
                        $arg1[0] = "Output Suffix";
                        $suffix = $arg1[1];
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;
                        case "pathway":
                        $arg1[0] = "Pathway ID";
                        $val = DB::select(DB::raw("select concat(concat(pathway_id,\"-\"),pathway_desc) as path from pathway where pathway_id like '$arg1[1]' LIMIT 1"));
                        if (sizeof($val) > 0) {
                            $arg1[1] = $val[0]->path;
                        }
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td><div style="width: 500px;white-space: normal;width: 560px;font-size: 14px;word-wrap: break-word;">{{$arg1[1]}}</div></td>
                        </tr>
                        <?php
                        break;
                        case "kegg":
                        $arg1[0] = "Kegg Native";
                        if ($arg1[1] == 'T') {
                            $arg1[1] = "True";
                        } else {
                            $arg1[1] = "False";
                        }
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;
                        case "layer":
                        $arg1[0] = "Same Layer";
                        if ($arg1[1] == 'T') {
                            $arg1[1] = "True";
                        } else {
                            $arg1[1] = "False";
                        }
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;
                        case "gdisc":
                        $arg1[0] = "Descrete Gene";
                        if ($arg1[1] == 'T') {
                            $arg1[1] = "True";
                        } else {
                            $arg1[1] = "False";
                        }
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;
                        case "cdisc":
                        $arg1[0] = "Descrete Compound";
                        if ($arg1[1] == 'T') {
                            $arg1[1] = "True";
                        } else {
                            $arg1[1] = "False";
                        }

                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;
                        case "split":
                        $arg1[0] = "Split Group";
                        if ($arg1[1] == 'T') {
                            $arg1[1] = "True";
                        } else {
                            $arg1[1] = "False";
                        }
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;
                        case "expand":
                        $arg1[0] = "Expand Node";
                        if ($arg1[1] == 'T') {
                            $arg1[1] = "True";
                        } else {
                            $arg1[1] = "False";
                        }
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;
                        case "multistate":
                        $arg1[0] = "Multi State";
                        if ($arg1[1] == 'T') {
                            $arg1[1] = "True";
                        } else {
                            $arg1[1] = "False";
                        }
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;
                        case "matchd":
                        $arg1[0] = "Match Data";
                        if ($arg1[1] == 'T') {
                            $arg1[1] = "True";
                        } else {
                            $arg1[1] = "False";
                        }
                        ?>
                        <tr>id:561fb76cbb76f
                            <td><b>{{$arg1[0]}}</b></td>
                            <td>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;
                        case "offset":
                        $arg1[0] = "Compound Label Offset";
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;
                        case "kpos":
                        $arg1[0] = "Key Position";
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;
                        case "pos":
                        $arg1[0] = "Signature Position";
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;
                        case "align":
                        $arg1[0] = "Key Alignment";
                        if ($arg1[1] == 'x') {
                            $arg1[1] = "X - Axis";
                        } else {
                            $arg1[1] = "y - Axis";
                        }
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;
                        case "glmt":
                        $arg1[0] = "Gene Limit";
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;
                        case "glmtlow":
                        $arg1[0] = "Gene Low";
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;
                        case "glmthigh":
                        $arg1[0] = "Gene High";
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td>{{str_replace(';',', ',$arg1[1])}}</td>
                        </tr>
                        <?php
                        break;
                        case "clmt":
                        $arg1[0] = "Compound Limit";
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td>{{str_replace(';',', ',$arg1[1])}}</td>
                        </tr>
                        <?php
                        break;
                        case "gbins":
                        $arg1[0] = "Gene Bins";
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;
                        case "cbins":
                        $arg1[0] = "Compound Bins";
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;
                        case "clow":
                        $arg1[0] = "Compound Low";

                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td style='background-color:{{$arg1[1]}}'>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;
                        case "cmid":
                        $arg1[0] = "Compound Mid";
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td style='background-color:{{$arg1[1]}}'>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;
                        case "chigh":
                        $arg1[0] = "Compound High";
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td style='background-color:{{$arg1[1]}}'>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;
                        case "glow":
                        $arg1[0] = "Gene Low";
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td style='background-color:{{$arg1[1]}}'>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;
                        case "gmid":
                        $arg1[0] = "Gene Mid";
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td style='background-color:{{$arg1[1]}}'>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;
                        case "ghigh":
                        $arg1[0] = "Gene High";
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td style='background-color:{{$arg1[1]}}'>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;
                        case "nsum":
                        $arg1[0] = "Node Sum";
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;
                        case "ncolor":
                        $arg1[0] = "NA Color";
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;
                        case 'filename':
                        $arg1[0] = "Gene Data";
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td>Click here to view/Download the file: <a target="_blank"
                                                                         href='{{$_SESSION["workingdir"]}}/{{$arg1[1]}}'>{{$arg1[1]}}</a>
                            </td>
                        </tr>
                        <?php
                        break;
                        case 'cfilename':

                        $arg1[0] = "Compound Data";
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td>Click here to view/Download the file: <a target="_blank"
                                                                         href='{{$_SESSION["workingdir"]}}/{{$arg1[1]}}'>{{$arg1[1]}}</a>
                            </td>
                        </tr>
                        <?php
                        break;
                        default:
                        ?>
                        <tr>
                            <td><b>{{$arg1[0]}}</b></td>
                            <td>{{$arg1[1]}}</td>
                        </tr>
                        <?php
                        break;
                        } //switch
                        } //if
                        } //if
                        } //for

                        ?>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <input type="text" value="" id="status" hidden="">
        <div id="progress" class="col-md-4" >

            <h2 class="alert alert-info">  Executing, Please wait. </h2>
<img src='images/load.gif' id='progressImage' hidden=""/>
            <div class="progress">
                <div class="progress-bar" role="progressbar" id="progressData" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                    0%
                </div>
            </div>
        </div>
        <div id="waiting" class="col-md-4" >

            <h2 class="alert alert-info"> Waiting..</h2>
            <img width="200px" hieght="200px" src="/images/hourglass.gif">
        </div>
        <div id="completed" class="col-md-4">
            <h2 class="alert alert-success " style="color:black;"> Completed </h2>

            <p>Click to see the output Generated and Logs under execution</p>
            <a href="resultview?analyses={{$_SESSION['analyses_id']}}&id={{$species}}&suffix={{$suffix}}">Analysis
                Results and logs</a>
            <img src="/images/checked.png">
            <?php
            if(Auth::user())
            {
                $user = Auth::user();
                echo "<h2 class='alert alert-info' style='font-size: 18px;color:black;'>Results were also emailed to $user->email </h2>";
            }
            else {
                        Session::put('anal_id', $_SESSION['analyses_id']);
                        echo "<h2 style='font-size:18px;color:black;' class='alert alert-info' ><a style='color:blue;font-size:18px;' href='/auth/register'>Register</a> or <a style='color:blue;font-size:18px;' href='/auth/login'>Login</a> to save the outputs and a lot more.<h2>";
                    }
            ?>
        </div>
    </div>

@stop
