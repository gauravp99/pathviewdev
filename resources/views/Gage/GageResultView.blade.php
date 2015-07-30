@extends('GageApp')


@section('content')
<?php
$argument = $_SESSION['argument'];
$analysis_id = "";
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


        if($keyAndValue[0] =='destDir')
        {
            if(Auth::user())
                {
                $user = Auth::user()->email ;
                }
            else{
                $user = "demo";
            }
            echo $analysis_id;
            $analysis_id = substr($keyAndValue[1],strlen(public_path()."/all/".$user."/"));
            continue;
        }
        else if($keyAndValue[0] == 'destFile' )
            {

            }
        else{
            echo "<tr><td>".$keyAndValue[0]."</td><td>".$keyAndValue[1]."</td></tr>";
        }

    }

}
echo "</tbody></table>";?>
<div id="progress" class="col-md-4">
    <h2 class="alert alert-info"> Executing, Please wait. </h2>
    <img width="200px" hieght="200px" src="/images/load.gif">
</div>
<div id="completed" class="list-group col-md-4">
    <p> Completed Gage Analysis</p>

    <a href="/gageResult?analysis_id={{$analysis_id}}" target="_blank">Click here to see results</a>
    <ul class="nav navbar-nav" style="margin-left: 30px;">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle list-group-item active" style="width:100%;"
               data-toggle="dropdown">Click here to see Analysis Logs</a>
            <ul class="dropdown-menu" style="width:100%;">


            </ul>
        </li>
    </ul>
</div>
<script>
    //ajax call to check if the analysis is done by the queue listener check is done on every 3 seconds once return true checking is closed

    $(document).ready(function () {
        $("#completed").hide();

        var argument ="<?php echo explode('/',$analysis_id)[0];?>";



        var myVar1 = setInterval(function () {
            $.ajax({
                url: "/ajax/GageanalysisStatus",
                method: 'POST',
                data: {
                    'analysisid':argument
                },
                success: function (data) {
                    if (data.length > 0) {
                        console.log("success: data " +  data);
                        $("#progress").show();
                        $("#completed").hide();
                        if (data === "true") {
                            clearInterval(myVar1);
                            $("#progress").remove();
                            $("#completed").show();
                        }
                    }
                },
                error: function (data) {
                    console.log("error"+data);
                }
            });
        }, 3000);
    });

</script>

    @stop
