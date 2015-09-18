<!-- Admin profile page-->

<?php
/**
 * Created by PhpStorm.
 * User: ybhavnasi
 * Date: 8/27/15
 * Time: 12:39 PM
 */?>

@extends('app')
@section('content')
    <style>
        .file{
            visibility:hidden;
            position:absolute;
        }
        h3{
            margin:0 0 20px 0;
            color:#428bca;
        }
        .error{
            font-size: 14px;
            color:#a94442;
        }
    </style>
    <script src="js/jquery.validate.min.js"></script>
<?php
        $users_email_string = "";
$users = DB::table('users')->get();
        foreach($users as &$value)
            {
                $users_email_string .= $value->email .";";
            }
        $users_email_string = trim($users_email_string);

?>

    <div class="col-sm-12">
        <h2 style="font-family:fantasy;">Send message/Broadcast message to all users</h2>
        <div class="col-sm-8">
            <div class="col-sm-8" style="padding:40px;margin:40px;">
                <form class="form-horizontal" id="form-contact" role="form" method="post"  accept-charset="UTF-8" enctype="multipart/form-data" action="/adminBroadcastMessage">

                    <div class="form-group">
                        <label for="users" class="col-sm-2 control-label">Users</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" rows="4" name="usersList"  >{{$users_email_string}}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message" class="col-sm-2 control-label">Subject</label>
                        <div class="col-sm-10">
                            <input type="text" id="form-control" class="form-control"  placeholder="subject">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message" class="col-sm-2 control-label">Message</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" rows="4" name="message"></textarea>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2" >
                            <input id="submit" name="submit" style="width:220px;font-size: 20px;" type="submit" value="Send" class="btn btn-primary">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <! Will be used to display an alert to the user>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-sm-4">
            <img class="first-slide" src="images/message.jpg" alt="First slide" style="opacity: 0.4;
    filter: alpha(opacity=40);width:60%;height:60%; ">
        </div>
    </div>
    <script>
        $(document).on('click', '.browse', function(){
            var file = $(this).parent().parent().parent().find('.file');
            file.trigger('click');
        });
        $(document).on('change', '.file', function(){
            $(this).parent().find('#form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
        });
        $('#form-contact').validate({

            invalidHandler: function (form, validator) {
                var errors = validator.numberOfInvalids();

                if (errors > 0) {
                    $('#progress').hide();
                    $("#error-message").show().text("** You missed " + errors + " field(s) or errors check and fill it to proceed.");
                    $("");
                } else {
                    $("#error-message").hide();
                }
            },
            rules: {
                image: {
                    required:false,
                    extension: "jpg|jpeg|png"
                },
                message:{
                    required:true
                },
                name:{
                    required:true
                },
                email:{
                    required:true,
                    email:true
                }
            },
            messages: {}
           /*, submitHandler: function(form) {
                var fd = new FormData(document.querySelector("form"));
                $.ajax({
                    url:$('#form-contact').attr('action'),
                    type:$('#form-contact').attr('method'),
                    data: fd,
                    success: function(data){
                        alert("successfully mailed");
                    },
                    contentType: false,
                    processData: false
                }); }*/

        });
    </script>
@stop