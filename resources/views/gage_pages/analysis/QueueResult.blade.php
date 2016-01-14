@extends('GageApp')


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
        $("#completed").hide();

        if($('#status').val())
        {
            $("#waiting").remove();
            $("#progress").hide();
            $("#completed").show();
        }else {
            $('#status').val("visited");

            var myvar = "<?php echo $analysisid;?>";
            var argument = "<?php echo $_SESSION['argument'];?>";
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
                            url: "/ajax/gageWaitingAnalysisStatus",
                            method: 'POST',
                            data: {
                                'analysisid': myvar
                            },
                            success: function (data) {
                                console.log("Waiting job status " + data);


                                $("#completed").hide();

                                if (data === "pushedJob") {
                                    console.log("job pushed " + data);
                                    $("#waiting").remove();
                                    $("#progress").show();
                                    waitFlag1 = true;
                                    clearInterval(myVar2);
                                    var myVar1 = setInterval(function () {
                                        j = j + 1;
                                        $.ajax({
                                            url: "/ajax/gageAnalysisStatus",
                                            method: 'POST',
                                            data: {
                                                'analysisid': myvar
                                            },
                                            success: function (data) {
                                                if (data.length > 0) {
                                                    console.log("success: data " + data);
                                                    $("#waiting").remove();
                                                    $("#progress").show();
                                                    $("#completed").hide();
                                                    if (data === "true") {

                                                        if(Math.round((j * 10)/factor) < 100)
                                                        {

                                                            $('#progressData').text("80%");
                                                            $('#progressData').attr('aria-valuenow', '80');
                                                            $('#progressData').css('width', '80%');
                                                            setTimeout(function delayFun() {
                                                                $('#progressData').text("100%");
                                                                $('#progressData').attr('aria-valuenow', '100');
                                                                $('#progressData').css('width', '100%');
                                                                $("#progress").remove();
                                                                $("#completed").show();
                                                            },2000);
                                                        }
                                                        else{
                                                            $("#progress").remove();
                                                            $("#completed").show();
                                                        }

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
                                                            $('#progressData').text("...");
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
                                    }, 1500  );
                                }

                            },
                            error: function (data) {
                                console.log("error" + data);
                            }
                        });
                    }, 1000);
                }


                if (queue_id > 0 || waitFlag1) {

                    console.log("in progress method");
                    $("#completed").hide();
                    if(queue_id > 0 )
                        $("#waiting").remove();

                    if( waitFlag1 ||queue_id > 0)
                        $("#progress").show();
                    var myVar1 = setInterval(function () {
                        j = j + 1;
                        $.ajax({
                            url: "/ajax/gageAnalysisStatus",
                            method: 'POST',
                            data: {
                                'analysisid': myvar
                            },
                            success: function (data) {
                                if (data.length > 0) {
                                    console.log("success: data " + data);
                                    $("#waiting").remove();
                                    $("#progress").show();
                                    $("#completed").hide();
                                    if (data === "true") {

                                        if(Math.round((j * 10)/factor) < 100)
                                        {

                                            $('#progressData').text("80%");
                                            $('#progressData').attr('aria-valuenow', '80');
                                            $('#progressData').css('width', '80%');
                                            setTimeout(function delayFun() {
                                                $('#progressData').text("100%");
                                                $('#progressData').attr('aria-valuenow', '100');
                                                $('#progressData').css('width', '100%');
                                                $("#progress").remove();
                                                $("#completed").show();
                                            },2000);
                                        }
                                        else{
                                            $("#progress").remove();
                                            $("#completed").show();
                                        }

                                        clearInterval(myVar1);

                                    } else {

                                        var fac = Math.round((j * 10)/factor);
                                        console.log(fac);
                                        if(fac < 1)
                                        {
                                            fac = 1;
                                        }
                                        if(fac > 100)
                                        {

                                            $('#progressData').css("opacity","0.4");
                                            $('#progressImage').show();
                                            $('#progressData').text("...");
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
                    }, 1500  );

                } else if(!waitFlag) {

                    $('#completed').show();
                }
            }else{
                $("#waiting").remove();
                $("#progress").remove();

            }

        }
    });


</script>
@include('GageNavigation')

<div class="col-md-10">
    <div class="col-md-8">
        <div class="placeholders ">
            <h1> Analysis Results </h1>

            <h2>Analysis ID:{{$analysisid}}</h2>
            <?php
            if(!Auth::user())
            {
                Session::put('anal_id',$analysisid);
            }

            ?>
            <h1> Analysis Input arguments </h1>
            <br/>

            <div class="bs-example" data-example-id="condensed-table">
                <?php

                //split
                $args = array();
                $argument = $_SESSION['argument'];
                $isDiscrete = $_SESSION['isDiscrete'];
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
    <div id="waiting" class="col-md-4" style="display: none;">

        <h2 class="alert alert-info"> Waiting..</h2>
        <img width="200px" hieght="200px" src="/images/hourglass.gif">
    </div>
    <div id="completed" class="col-md-4" style="display: none;">
        <h2 class="alert alert-success " style="color:black;"> Completed </h2>

        <p>Click to see the output Generated and Logs under execution</p>

        <a href="gageResult?analysis_id={{$analysisid}}&discrete={{$isDiscrete}}">Analysis
            Results and logs</a>
        <img src="/images/checked.png">
        <?php
        if(Auth::user())
        {
            $user = Auth::user();
            echo "<h2 class='alert alert-info' style='font-size: 18px;color:black;'>Results were also emailed to $user->email </h2>";
        }
        else {
            Session::put('anal_id', $analysisid);
            echo "<h2 style='font-size:18px;color:black;' class='alert alert-info' ><a style='color:blue;font-size:18px;' href='/auth/register'>Register</a> or <a style='color:blue;font-size:18px;' href='/auth/login'>Login</a> to save the outputs and a lot more.<h2>";
        }
        ?>
    </div>
</div>

@stop
