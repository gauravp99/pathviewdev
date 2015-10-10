@extends('app')
@section('content')

    @include('navigation')
    <div class="col-md-10">
        <div class="col-md-8">
            <h1> Analysis Results </h1>
            <h2>Analysis ID:{{$_GET['analyses']}}</h2>
    <?php
        use App\Commands\SendJobAnalysisCompletionMail;
        if(Auth::user())
        {
        $directory = public_path() ."/all/".Auth::user()->email."/".$_GET['analyses'];
        $directory1 = "/all/".Auth::user()->email."/".$_GET['analyses'];
        }
        else if( isset($_GET['email']))
        {
        $directory = public_path() ."/all/".$_GET['email']."/".$_GET['analyses'];
        $directory1 = "/all/".$_GET['email']."/".$_GET['analyses'];
        }
        else {
            $directory = public_path() ."/all/demo/".$_GET['analyses'];
            $directory1 = "/all/demo/".$_GET['analyses'];
        }
            $path = realpath($directory);
            $analysis = $_GET['analyses'];

            $argument ="";

            $analyses = array();
            if(Auth::user())
            {
                $analyses = DB::table('analysis')->where(['id' => Auth::user()->id,'analysis_id'=>$analysis])->get();
            }
            else
            {
                $user_id = DB::table('users')->where('email',$_GET['email'])->get();
                if(sizeof($user_id) >0)
                {
                    $analyses = DB::table('analysis')->where(['id' => $user_id[0]->id,'analysis_id'=>$analysis])->get();
                }

            }
                if(sizeof($analyses) > 0)
                    {
            $argument = $analyses[0]->arguments;}
                else{
                    return "Not able to find the analysis daetails";
                }
            // If it exist, check if it's a directory
            if($path !== false AND is_dir($path))
            {


        $contents = scandir($directory);
        if ($contents) {
            foreach ($contents as $key => $value) {
                if ($value == "." || $value == "..") {
                    unset($key);

                }
            }
        }

        echo "<h2>Pathview Graphs:</h2>";


        foreach ($contents as $k => $v) {

        if (strpos($v, $_GET['suffix'])) {
        $val = $v;
        $id = substr($val, 0, 8);
            $analysis= $_GET['analyses'];
            if (strpos($val, 'png')) {
                if(Auth::user())
                    {
                    $email = Auth::user()->email;
                echo "<li  style='font-size: 24px; list-style-type: none;'>  <a target=\"_blank\" href=\"viewer?analyses=$analysis&id=$id&image=$val&email=$email\" > " . $val . "</a></li>";
                        }
                else
                    {
                        $email = $_GET['email'];
                        echo "<li  style='font-size: 24px; list-style-type: none;'>  <a target=\"_blank\" href=\"viewer?analyses=$analysis&id=$id&image=$val&email=$email\" > " . $val . "</a></li>";
        }

        }
            else {
                echo "<li  style='font-size: 24px;list-style-type: none;'>  <a target=\"_blank\" href=\"$directory1/" . $val . "  \">$val</a></li>";

            }

        }

        }

}
else
    {

        if(sizeof($analyses)>0)
        {

            echo "<h3 class='alert alert-info'> Analysis Output files were deleted Analysis are submitted again please wait. while we regenerate them </h3>";
            $runHashdata = array();
            $runHashdata['argument'] = $analyses[0]->arguments;
            $runHashdata['destFile'] = $directory;
            $runHashdata['anal_type'] = "Analysis History regenerated";
            if (Auth::user())
                $runHashdata['user'] = Auth::user()->id;
            else
                $runHashdata['user'] = "demo";
            Redis::set($analysis, json_encode($runHashdata));
            Redis::set($analysis.":Status","false");
            $process_queue_id = Queue::push(new SendJobAnalysisCompletionMail($analysis));
/**
 *  <script>


$(document).ready(function () {
$("#completed").hide();
var myvar = "                var myvar = "<?php echo $analysis;?>";
var id ="<?php echo $species; ?>";
var suffix ="<?php echo $suffix;?>";
var argument ="<?php echo $argument;?>";
var anal_type="<?php echo $anal_type;?>";


var myVar1 = setInterval(function () {
$.ajax({
url: "/ajax/analysisStatus",
method: 'POST',
data: {'analysisid': myvar,
'id':id,
'suffix':suffix,
'argument':argument,
'anal_type':anal_type
},
success: function (data) {
if (data.length > 0) {
console.log("success: data " +  data);
$("#progress").show();
$("#completed").hide();
if (data === "true") {
clearInterval(myVar1);
$("#progress").hide();
$("#completed").show();

}
}
},
error: function (data) {
console.log("error");
}
});
}, 1000);
});
</script>
 */
        }


    }





        ?>
        <h1> Analysis Input arguments </h1>
        <br/>
        <div class="bs-example" data-example-id="condensed-table">
            <table border=1 style="width:70%" class="table table-condensed">
                <thead>
                <tr>
                    <th>Argument Name</th>
                    <th>Value</th>

                    <tbody>


                    <?php
                    $arguments = array();
                    $arguments = explode(";", $argument);
                    $arguments = array_unique($arguments);
                $species="";
                $suffix="";
                $anal_type="regenerated";

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
                    $species= $arg1[1];

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
                $suffix =$arg1[0];
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
                    <td>{{$arg1[1]}}</td>
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
                <tr>
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
                                                                 href='{{$directory1}}/{{$arg1[1]}}'>{{$arg1[1]}}</a>
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
                                                                 href='{{$directory1}}/{{$arg1[1]}}'>{{$arg1[1]}}</a>
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

                }


                }
                } }


                ?>

                </tbody>
            </table>
        </div>

    </div>
    <div id="progress" class="col-md-4" hidden>
        <h2 class="alert alert-info"> Executing, Please wait. </h2>
        <img width="200px" hieght="200px" src="/images/load.gif">
    </div>
    <div id="completed" class="col-md-4" hidden>
        <h2 class="alert alert-success " style="color:black;"> Completed </h2>

        <p>Click to see the output Generated and Logs under execution</p>
        <a href="resultview?analyses={{$analysis}}&id={{$species}}&suffix={{$suffix}}">Analysis
            Results and logs</a>
        <img src="/images/checked.png">
    </div>
</div>





@stop
