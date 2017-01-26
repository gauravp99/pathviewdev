
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
    @if (Session::has('failure'))
       <h1 class="danger" style="color:#a94442;"> {{ session('failure') }} </h1>
    @endif
    @if (session('message'))
       <h1 class="success" style="color:rgb(65, 134, 58);"> {{ session('message') }} </h1>
    @endif

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
	error_log('--------------------------------------');
        if (empty($analyses[0]->arguments)) {
            echo "No recent Activity's";
        } else { ?>

        <div class="col-md-12 content" style="text-align: center;">
            <h3 class="arg_content">Analysis Owned by user</h3>
        </div>

        <div class="col-sm-12">

            <div class="col-sm-5">
                {!! $analyses->render() !!}
            </div>
            <!-- <div class="col-sm-5">
                <div class="btn-group" style=""><a href='#' data-id='' data-toggle='modal' id='Analysisdelete' data-analysisID="" style='display:none;' class='delete' data-target='#myModal'>
                        <button type="button" class="btn btn-default dropdown-toggle" ><span class='glyphicon glyphicon-trash'> Delete</span></button>
                    </a>
                </div>
            </div>-->

        </div>

        <table class="table table-bordered" style="padding:10px;width:90%;margin:auto;">
            <thead>
           <tr>
           <td>
	   <div  style="margin-left:55px;margin-bottom:5px;">
                <div class="btn-group" style=""><a href='#' data-id='' data-toggle='modal' id='Analysisdelete' data-analysisID="" style='display:none;' class='delete' data-target='#myModal'>
                        <button type="button" class="btn btn-default dropdown-toggle" ><span class='glyphicon glyphicon-trash'> Delete</span></button>
                    </a>
                </div>
            </div>
           </td>
           <td>
	   <div  style="margin-left:55px;margin-bottom:5px;">
                <div class="btn-group" style=""><a href='#' data-id='' data-toggle='modal' id='Analysisshare' data-analysisID="" style='display:none;' class='share' data-target='#myModal1'>
                        <button type="button" class="btn btn-default dropdown-toggle" ><span class='glyphicon glyphicon-share'> Share</span></button>
                    </a>
                </div>
            </div>
           </td>
            </tr>
	    <tr>
		<th><input type="checkbox" name="select" id="selectAll"><span   id="selectAlltext" >  Select All</span></th> 

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
                echo "<tr><td><input type='checkbox' id='analID' alue=$analyses1->analysis_id name=$analyses1->analysis_id ></td>";
                echo "<td>$analyses1->analysis_id</td><td><h4> $analyses1->analysis_type </h4></td>";

                echo "<td> ".floor($date_diff / (60 * 60 * 24))." days ago ";
                $directory = get_string_between($analyses1->arguments, "targedir:", ";");
                $dir = get_string_between($analyses1->arguments, public_path(), ";");
                $id = get_string_between($analyses1->arguments, "species:", ";");
                $suffix = get_string_between($analyses1->arguments, "suffix:", ";");
                echo "</td>";
                if((strcmp($analyses1->analysis_origin,'pathview')==0) || (strcmp($analyses1->analysis_origin,'pathview_restapi')==0))
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
        </table>
    <div class="col-md-12 content" style="text-align: center;">
        <h3 class="arg_content">Analysis Shared by other user</h3>
    </div>
        <?php
        if (empty($shared_analyses[0]->shared_analysis_id)) {
            echo "No recent Shared Analysis";
        } else { ?>

        <table class="table table-bordered" style="padding:10px;width:90%;margin:auto;">
            <thead>
	    <tr>
                <th>#</th>
                <th>Analysis Type</th>
                <th>Number of days</th>
                <th>Result access</th>
                <th>Analysis Size</th>
                <th>Shared By</th>
            </tr>
            </thead>
            <?php
            $i = 1;
            foreach ($shared_analyses as $shared_analyses1) {
                $now = time(); // or your date as well
                $your_date = strtotime(str_split($shared_analyses1->created_at, 10)[0]);
                $date_diff = $now - $your_date;
            	$arguments_shared= DB::select(DB::raw("select arguments,analysis_type,analysis_origin from analysis where analysis_id='$shared_analyses1->shared_analysis_id' limit 1"));
            	$shared_analysis_owner= DB::select(DB::raw("select email from users where id=$shared_analyses1->owner limit 1"));
		$shared_analysis_origin=$arguments_shared[0]->analysis_origin;
                echo "<td>$shared_analyses1->shared_analysis_id</td><td> {$arguments_shared[0]->analysis_type} </td>";
                echo "<td> ".floor($date_diff / (60 * 60 * 24))." days ago ";
                $directory = get_string_between($arguments_shared[0]->arguments, "targedir:", ";");
                $dir = get_string_between($arguments_shared[0]->arguments, public_path(), ";");
                $id = get_string_between($arguments_shared[0]->arguments, "species:", ";");
                $suffix = get_string_between($arguments_shared[0]->arguments, "suffix:", ";");
                echo "</td>";
                if(strcmp($shared_analysis_origin,'pathview')==0)
                    {
            ?>
               <td><p>  <a href=/anal_hist?analyses={{$shared_analyses1->shared_analysis_id}}&id={{$id}}&suffix={{$suffix}}&shared_analysis='T'>Analysis:{{$shared_analyses1->shared_analysis_id}}</a> </p></td>
            <?php
                        }
                else if(strcmp($shared_analysis_origin,'gage')==0)
                    {
                        echo "<td><p><a href=/gage_hist?analyses=$shared_analyses1->shared_analysis_id>Analysis $shared_analyses1->shared_analysis_id</a> </p></td>";

                    }
                $f = './all/' . $shared_analysis_owner[0]->email;
                $io = popen('/usr/bin/du -sk ' . $f.'/'.$shared_analyses1->shared_analysis_id, 'r');
                $size = fgets($io, 4096);
                $size = substr($size, 0, strpos($size, "\t"));
                pclose($io);
                $size = (intval($size))/1024;
                echo "<td>".number_format((float)$size, 2, '.', '')." MB</td>";
                echo "<td> {$shared_analysis_owner[0]->email} </td></tr>";
	    } }?>
        </table>
        </div>

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
                        {!! form::open(array('url' => '/analysisDelete','method'=>'POST','id'=>'deleteAnalysis')) !!}
                        <input type="text" name="analysisID" id="analysisID" value="" hidden/>
                        <input type="submit" value="OK" class="btn btn-default" >
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel
                        </button>
			{!! form::close() !!}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
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
                        {!! form::open(array('url' => '/analysisShare','method'=>'POST','id'=>'shareAnalysis')) !!}
                        <label class="col-md-4 control-label">Email Address</label>
                        <input type="text" name="emailID" id="emailID" value="" />
                        <input type="text" name="analysisID" id="analysisID" value="" hidden/>


                    </div>
                    <div class="modal-footer">
                        <input type="submit" value="Share" class="btn btn-default" id='shardsubmitid'>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close
                        </button>
			{!! form::close() !!}
                    </div>
                </div>
            </div>
        </div>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="/js/bootcomplete.js/dist/jquery.bootcomplete.js"></script>
<script>


    $(function(){
           //$('#emailID').keyup(function ()
//	   $('#emailID')
//		       .keyup(myFunction)
//		       .keydown(myFunction)
//		           .keypress(myFunction)
//			       .blur(myFunction)
//			           .change(myFunction)
//				   .click(myfunction)
	  // var myFunction= function(){



	   //};

           $('#emailID').on('input click keyup keydown keyup keypress blur change', function(event) 
           {
	     //alert(event.type);
	      var emailids=$('#emailID').val();
              //var emailSplit = emailids.split(/[\s;,]+/); 
              var emailSplit = emailids.split(/[;,]+/); 
              var patterna=/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/;
              for (i in emailSplit)
              {
                  if(patterna.test(emailSplit[i]))
                  {
                    $('#shardsubmitid').removeAttr("disabled");
                  }
                  else
                  {
                     $('#shardsubmitid').attr('disabled', true);
                     break;
                  }
              }

            });

	   //$("#emailID").autocomplete({
	   //    source: emailid,
           //    open: function(event, ui) { $(".ui-slider-handle").css("z-index", -1); },
           //    close: function(event, ui) { $(".ui-slider-handle").css("z-index", 2); }
           //});
        //unselect all checkbox on load selects the checkbox if already checked and refreshed
        $.each($( ":checkbox"),function(id,element){
            element.checked = false;
        });

$('#Analysisdelete, #Analysisshare').click(function(){
 var Ids = $(this).attr('data-analysisID');
     console.log(Ids);
	$(".modal-body #analysisID").val( Ids);

});



	//select all checkbox 
        $('#selectAll').change(function(){
            if(this.checked)
            {
                var analysisIDText = "";
                $.each($( ":checkbox"),function(id,element){
                    element.checked = true;

                    if(element.id==="analID")
                    analysisIDText += element.name+",";

                });
		
		//setting the analysis id's in the form element
		console.log(analysisIDText);
		$('#Analysisdelete,#Analysisshare').attr('data-analysisID',analysisIDText);
                $('#Analysisdelete,#Analysisshare').show();
		
            }
            else{
                $.each($( ":checkbox"),function(id,element){
                    element.checked = false;

                });
                $('#Analysisdelete,#Analysisshare').hide();
            }


        });

        var analy_ids="";


        $('input[type="checkbox"]').change(function() {
        
	var vflag = false;    
	if(this.checked && $(this).attr('id') != 'selectAll') {
                analy_ids += $(this).attr('name');
                analy_ids +=",";
                console.log(analy_ids);
                $('#Analysisdelete,#Analysisshare').show();
		$('#Analysisdelete,#Analysisshare').attr('data-analysisID',analy_ids);
            }else if($(this).attr('id') != 'selectAll'){
                var countOfChecked = 0;
                analy_ids = "";
		$.each($( ":checkbox"),function(id,element){
                
		        if(element.checked)
                        {
			    analy_ids += element.name+",";
                            countOfChecked++;
                        }
                });
		
		
                if(countOfChecked == 0)
                {
                    $('#Analysisdelete,#Analysisshare').hide();
                }
            }else{
		vflag = true;
			}
		if(!vflag)
		{
		console.log(analy_ids);
		$('#Analysisdelete,#Analysisshare').attr('data-analysisID',analy_ids);
		}
        });

    });



    </script>
@stop


