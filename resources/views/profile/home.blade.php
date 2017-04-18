
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
            <script>
                var msg = "<h1 class='danger' style='color:#a94442;'> {{ session('failure') }} </h1>"
                msg+= "Send invite so they may view your analysis?"
                $(window).on("load",function(){
                    bootbox.confirm({
                        message:msg,
                        buttons: {
                            confirm: {
                                label: 'Yes',
                                className: 'btn-success'
                            },
                            cancel: {
                                label: 'No',
                                className: 'btn-danger'
                            }
                        },
                        callback: function (result) {
                            console.log(result);
                        }   
                    });
                });
            </script>
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

        </div>

        <!-- modified code starts here -->
        <div class="container" style="padding:5px;width:90%;margin-left:5%; float:left;">
           <div id="toolbar" class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle disabled" id='analysisShare' onclick="modalShare()"><span class='glyphicon glyphicon-export glyphicon-share'> Share</span></button>
            <button type="button" class="btn btn-danger dropdown-toggle disabled" id='analysisDelete' onclick="modalDelete()" ><span class='glyphicon glyphicon-trash'> Delete</span></button>
          </div> 


          <table class="table table-bordered" id="table"
          data-toggle="table"
          data-mobile-responsive="true"
          data-click-to-select="true"
          data-search="true"
          data-toolbar="#toolbar"
          >
              <thead>
  	    <tr>
                  <th data-field="state" data-checkbox="true"></th>
                  <th data-field="id" data-visible="false">ID</th>
                  <th data-field="name" data-sortable="true" data-searchable="true">Name</th>
                  <th data-field="type" data-sortable="true">Type</th>
                  <th data-field="days" data-sortable="true" data-filter-control="select">Time</th>
                  <th data-field="result" data-sortable="true">Results</th>
                  <th data-field="size" data-sortable="true">Size</th>
              </tr>
              </thead>
              <?php
              $i = 1;
              foreach ($analyses as $analyses1) {
                  $now = time(); // or your date as well
                  $your_date = strtotime(str_split($analyses1->created_at, 10)[0]);
                  $date_diff = $now - $your_date;

                  //echo "<tr><td><input type='checkbox' id='analID' alue=$analyses1->analysis_id name=$analyses1->analysis_id ></td>";
                  echo "<td class='bs-checkbox'><input data-index=$i name='btSelectItem' type='checkbox'></td>";
                  echo "<td>$analyses1->analysis_id</td>";
                  if(empty($analyses1->analysis_name)){
                      echo "<td>$analyses1->analysis_id</td>";
                  }else{
                      echo "<td>$analyses1->analysis_name</td>";
                  }
                  echo "<td><h4> $analyses1->analysis_type </h4></td>";
                  echo "<td> ".floor($date_diff / (60 * 60 * 24))." days ago ";
                  $directory = get_string_between($analyses1->arguments, "targedir:", ";");
                  $dir = get_string_between($analyses1->arguments, public_path(), ";");
                  $id = get_string_between($analyses1->arguments, "species:", ";");
                  $suffix = get_string_between($analyses1->arguments, "suffix:", ";");
                  $autosel = get_string_between($analyses1->arguments, "autosel:", ";");
                  echo "</td>";
                  if((strcmp($analyses1->analysis_origin,'pathview')==0) || (strcmp($analyses1->analysis_origin,'pathview_restapi')==0))
                      {
              ?>
                 <td><p>  <a href=/anal_hist?analyses={{$analyses1->analysis_id}}&id={{$id}}&suffix={{$suffix}}&autosel={{$autosel}}>Analysis:{{$analyses1->analysis_id}}</a> </p></td>
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
          </div>
      <div class="col-md-12 content" style="text-align: center;">
          <h3 class="arg_content">Analysis Shared by other user</h3>
      </div>
     <div class="container" style="padding:10px;width:90%;margin:auto; float:left;">
          <?php
          if (empty($shared_analyses[0]->shared_analysis_id)) {
              echo "No recent shared Analysis";
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
                  $autosel = get_string_between($arguments_shared[0]->arguments, "autosel:", ";");
                  echo "</td>";
                  if((strcmp($shared_analysis_origin,'pathview')==0) || (strcmp($shared_analysis_origin,'pathview_restapi')==0))
                      {
              ?>
                 <td><p>  <a href=/anal_hist?analyses={{$shared_analyses1->shared_analysis_id}}&id={{$id}}&suffix={{$suffix}}&autosel={{$autosel}}&shared_analysis='T'>Analysis:{{$shared_analyses1->shared_analysis_id}}</a> </p></td>
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
        </div>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!--this library is used for the modals-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>

<!-- this library and stylesheets are used to build the tables containing the analysis-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.css">

<!--MODAL FORMS START-->

<!--this form is used when the delete modal is confirmed-->
<form method="post" id="formDelete" action="/analysisDelete">
<input type="text" name="analysisID" id="formDeleteInput" value="" hidden/>
</form>

<!--this form is used when the share modal is confirmed-->
<form method="post" id="formShare" action="/analysisShare" style="display:none;">
<input type="text" name="analysisID" id="formShare_analysisID" value="" hidden/>
Please enter the email of the user you would like to share this analysis with:
<input type="email" name="emailID" id="formShare_email" value=""/>
</form>

<!--MODAL FORMS END-->

<script>
    // used to enable or disable sharing or deleting buttons
   $(function () {
    //for any event that happens on the table this function gets called
    $('#table').on('all.bs.table', function (e, row, $elem) {

        //check to disable or disable buttons based on the number of selections
        //start
        var selections =  $('#table').bootstrapTable('getSelections');
        if (selections.length>0)
        {
            $('#analysisShare').removeClass("disabled");
            $('#analysisDelete').removeClass("disabled");

        };
        if (selections.length==0)
        {
            $('#analysisShare').addClass("disabled");
            $('#analysisDelete').addClass("disabled");

        };
       
    }); //end   
});//end of document ready function

//this form is called when the delete button is pressed
function modalDelete(){
        bootbox.confirm({
        title: "Delete confirm",
        message: "Do you want to delete selected analysis now? This cannot be undone.",
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> Cancel'
            },
            confirm: {
                label: '<span class="glyphicon glyphicon-trash"> Delete</span>',
                className:'btn btn-danger'
            }
        },
        callback: function (result) {
            if (result==true){
                var toDelete=getSelectionId();
                $('#formDeleteInput').val(toDelete);
                $('#formDelete').submit();
            }
        }
    });
}

//this form is called when the share button is pressed
function modalShare(){
        //select the form to display in the modal and remove display:none css property so it shows.
        form =$('#formShare');
        form.css("display","");

        bootbox.dialog({
        title: "Share Analyses",
        message: form,
        buttons: {
            cancel: {
                label: 'Cancel',
            },
            confirm: {
                label: '<span class="glyphicon glyphicon-share-alt"> Share</span>',
                className:'btn btn-success disabled modal-btn-share',
                //called when the share button is pressed
                callback: function(){
                    var toShare=getSelectionId();
                    //assign the email and analysis values to the form for submission
                    $('#formShare_analysisID').val(toShare);
                    console.log($('#formShare_email').val())
                    form.submit();
                }
            }
        }
    }).on('hide.bs.modal',function(e){
            //this is needed as bootbox.js removes everything in a modal once it closes otherwise
            form.css("display","none");
            form.hide().appendTo('body');
        });
            
}

//temporary function to validate that email field contains emails
//TODO: implement actual validation and use choosen library to autocomplete emails
$('#formShare_email').keyup(function(){
    if (($('#formShare_email').val()).length >3) {
        $('.btn-success').removeClass("disabled");
    }
});

//this function returns the selected elements id as a string from a bootstrapTable
function getSelectionId(){
    var selections =  $('#table').bootstrapTable('getSelections');
                //grabs the id from the selected row
                var tmp=[];
                selections.forEach(function(element) {
                    tmp.push(element.id);
                });

                //this if is needed as the backend expects every analysis ID to have a comma at the end.
                if(tmp.length > 1){
                    tmp = tmp.join(',');
                }
                else{
                    tmp = tmp.concat(",");
                }
                
                return tmp;
}
    </script>

@stop
