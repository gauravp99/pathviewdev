
@extends('app')

@section('content')
    <?php
    if(basename(Request::url())== "gage-home" || basename(Request::url())== "gage-guest-home")
    {

    ?>
    @include('GageNavigation')
<?php
}
        else {
            ?>@include('navigation')<?php
        }
    ?>

    <div class='col-md-2-result sidebar col-md-offset-2'>
        <h1 class="success" style="color:rgb(65, 134, 58);">{{Auth::user()->name}} profile </h1>
        <?php
        function xcopy($source, $dest, $permissions = 0755)
        {
            try{
            // Check for symlinks
            if (is_link($source)) {
                return symlink(readlink($source), $dest);
            }

            // Simple copy for a file
            if (is_file($source)) {
                return copy($source, $dest);
            }

            // Make destination directory
            if (!is_dir($dest)) {

                mkdir($dest, $permissions);


            }

            // Loop through the folder
            $dir = dir($source);
            while (false !== $entry = $dir->read()) {
                // Skip pointers
                if ($entry == '.' || $entry == '..') {
                    continue;
                }

                // Deep copy directories
                xcopy("$source/$entry", "$dest/$entry", $permissions);
            }

            // Clean up
            $dir->close();
            }
        catch (Exception $e) {
            echo "<h1 style='font-size: 10px' class='alert alert-danger'>Note : oops Sorry Not able to save your guest analyses made. Mail us will make sure to add it to your profile </h1>";

        }
            return true;
        }
        ?>
        @if(Session::get('anal_id')!=null)
            <?php
                if(!file_exists(public_path().'/all/'.Auth::user()->email))
                    {
                mkdir(public_path().'/all/'.Auth::user()->email);
                }

            xcopy(public_path().'/all/demo/'.Session::get('anal_id'),public_path().'/all/'.Auth::user()->email.'/'.Session::get('anal_id'));
            DB::table('analysis')
                    ->where('analysis_id', Session::get('anal_id'))
                    ->update(array('id' => Auth::user()->id));
            Session::forget('anal_id');
            ?>

        @endif
        <div class="col-md-12">
        <div class="col-md-6">
        @if(Session::get('error')!=NULL)

            <p class="alert alert-danger">
                You don't have enough space to do more analysis
            </p>

        @endif
        <p>User ID: {{Auth::user()->id }}</p>

        <p>User Email: {{Auth::user()->email }}</p>

        <p>User Created at: {{Auth::user()->created_at }}</p>
        <?php
        $f = './all/' . Auth::user()->email;
        $io = popen('/usr/bin/du -sk ' . $f, 'r');
        $size = fgets($io, 4096);
        $size = substr($size, 0, strpos($size, "\t"));

        pclose($io);
        $size = (100*1024 - intval($size))/1024;
        if($size < 10)
        {
        ?>
        <p class="alert alert-danger"><b>Remaining space:  {{ number_format((float)$size, 2, '.', '') }} MB </b></p>
        <?php } else { ?>
        <p ><b>Remaining space:  {{ number_format((float)$size, 2, '.', '') }} MB </b></p>

        <?php }?>
        </div>
            <div class="col-md-6">
        <div class="col-md-12">
            <a href="/edit_user/{{Auth::user()->id}}">
                <button type="submit" class="btn btn-primary" style="width:200px;font-size: 18px">
                   Edit Profile
                </button>
            </a>

        </div>

            <div class="col-md-12">
                <a href="/passwordReset">
                    <br/>
                    <button type="submit" class="btn btn-primary" style="width:200px;font-size: 18px">
                        Reset Password
                    </button>
                </a>
            </div>
            </div>
        </div>
        <h2 class="success">Recent activity: </h2>
        <?php


        function get_string_between($string, $start, $end)
        {
            $string = " " . $string;
            $ini = strpos($string, $start);
            if ($ini == 0) return "";
            $ini += strlen($start);
            $len = strpos($string, $end, $ini) - $ini;
            return substr($string, $ini, $len);
        }
        if (is_null($analyses)) {
            echo "No recenet Activity's";
        } else { ?>
        <table class="table table-bordered" style="padding:10px;width:90%;margin:auto;">
            <thead>
            <tr>
                <th><a href="#" id='deleteAll' data-toggle='modal'   data-target='#myModal'>Delete </a></th>
                <th></th>
                <th>#</th>
                <th>Analysis Type</th>
                <th>Number of days</th>
                <th>Result access</th>
                <th>Analysis Size</th>
            </tr>
            </thead>
            <?php
            $i = 1;
            foreach ($analyses as $analyses1) {
                $now = time(); // or your date as well
                $your_date = strtotime(str_split($analyses1->created_at, 10)[0]);
                $date_diff = $now - $your_date;
                echo "<tr><td><input type='checkbox' value=$analyses1->analysis_id name=$analyses1->analysis_id ></td>
                <td>
                <a href='#' data-id=$analyses1->analysis_id data-toggle='modal' id='Analysisdelete' class='delete' data-target='#myModal'>
                <span class='glyphicon glyphicon-trash'><span>
                </a>
                </td>";
                echo "<td>$analyses1->analysis_id</td><td><h4> $analyses1->analysis_type </h4></td>";

                echo "<td> ".floor($date_diff / (60 * 60 * 24))." days ago ";
                $directory = get_string_between($analyses1->arguments, "targedir:", ";");
                $dir = get_string_between($analyses1->arguments, public_path(), ";");
                $id = get_string_between($analyses1->arguments, "species:", ";");
                $suffix = get_string_between($analyses1->arguments, "suffix:", ";");
                echo "</td>";
                if(strcmp($analyses1->analysis_origin,'pathview')==0)
                    {
            ?>
               <td><p>  <a href=/anal_hist?analyses={{$analyses1->analysis_id}}&id={{$id}}&suffix={{$suffix}}>Analysis:{{$analyses1->analysis_id}}</a> </p></td>
            <?php
                        }
                else if(strcmp($analyses1->analysis_origin,'gage')==0)
                    {
                        echo "<td><p><a href=/gage_hist?analyses=$analyses1->analysis_id>Analysis $analyses1->analysis_id</a> </p></td>";

                    }
                $f = './all/' . Auth::user()->email;
                $io = popen('/usr/bin/du -sk ' . $f.'/'.$analyses1->analysis_id, 'r');
                $size = fgets($io, 4096);
                $size = substr($size, 0, strpos($size, "\t"));
                pclose($io);
                $size = (intval($size))/1024;
                echo "<td>".number_format((float)$size, 2, '.', '')." MB</td></tr>";


            }}?>

            {!! $analyses->render() !!}


        </table>
        </div>
            <input type="submit" id="delete" data-toggle='modal'  data-target='#myModal' value="Delete Selected" style="padding:5px;" hidden="">
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title " id="myModalLabel">Confirm</h4>
                    </div>
                    <div class="modal-body">
                        <p id="analysis"> </p>
                        <h3 class="alert alert-danger">Alert!! Clicking ok Will delete the files and lost. You will not be able to retrieve it back.</h3>
                        {!! form::open(array('url' => 'analysisDelete','method'=>'POST')) !!}
                        <input type="text" name="analysisID" id="analysisID" value="" hidden/>
                        <input type="submit" value="OK" class="btn btn-default" >
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel
                        </button>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close
                        </button>
                    </div>
                </div>
            </div>
        </div>


<script>


    $(function(){
        var analy_ids="";
        $('input[type="checkbox"]').change(function() {
            if(this.checked) {
                analy_ids += $(this).val();
                analy_ids +=",";
                console.log(analy_ids);
            }
            $('#delete').show();
        });
        $(document).on("click", ".delete", function () {
            var analysisID = $(this).data('id');
            $("#analysis").text("Deleting "+ analysisID);
            $(".modal-body #analysisID").val( analysisID );
        });
        $(document).on("click", "#deleteAll", function () {
            $(".modal-body #analysisID").val("");
            $('input[type=checkbox]').each(function() {

                analy_ids+= $(this).val();
                analy_ids +=",";
                console.log(analy_ids);
                $("#analysis").text("Deleting Analysis:deleting all");
                $(".modal-body #analysisID").val( analysisID );
            });
        });
        $(document).on("click", "#delete", function () {
            console.log('delete selected button clicked');
            $("#analysis").text("Deleting Analysis:"+analy_ids);
            $(".modal-body #analysisID").val( analy_ids );
        });

    });



    </script>
@stop


