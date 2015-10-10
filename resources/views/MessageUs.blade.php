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

    <div class="col-sm-12">
<h2 style="font-family:fantasy;"> Comments/Questions</h2>
        <div class="col-sm-10">
            <div class="col-sm-12" style="padding:40px;margin:40px;font-size: 25px;">
<form class="form-horizontal" id="form-contact" role="form" method="post"  accept-charset="UTF-8" enctype="multipart/form-data" action="/postMessage">
    <div class="form-group">
        <label for="name" class="col-sm-3 control-label">Name</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="name" name="name" placeholder="First & Last Name" value= @if(Auth::user())  {{Auth::user()->name}} @endif>
        </div>
    </div>
    <div class="form-group">
        <label for="email" class="col-sm-3 control-label">Email</label>
        <div class="col-sm-9">

            <input type="email" class="form-control" id="email" name="email" placeholder="example@domain.com" value= @if(Auth::user())  {{Auth::user()->email}} @endif >
        </div>
    </div>
    <div class="form-group">
        <label for="message" class="col-sm-3 control-label">Message</label>
        <div class="col-sm-9">
            <textarea class="form-control" rows="4" name="message"></textarea>
        </div>
    </div>
    <div class="form-group col-md-offset-5" >

        <input type="file" name="uploadimg" class="file">
        <label for="email" class="col-sm-3 control-label"><span class=""><i class="glyphicon glyphicon-paperclip"></i></span></label>
        <div class="input-group col-xs-6  col-sm-offset-2 " style="margin-left:18.5%;">
        <span class="input-group-addon"><i class="glyphicon glyphicon-picture"></i></span>
        <input type="text" id="form-control" class="form-control" disabled placeholder="image">
        <span class="input-group-btn">
          <button class="browse btn btn-primary" type="button"><i class="glyphicon glyphicon-search"></i> Browse</button>
        </span>
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
        messages: {},
        submitHandler: function(form) {
            var fd = new FormData(document.querySelector("form"));
            $.ajax({
                url:$('#form-contact').attr('action'),
                type:$('#form-contact').attr('method'),
                data: fd,
                success: function(data){
                    console.log(data);
                    alert("successfully mailed");
                },
                failure:function(data){
                  console.log(data);
                },
                contentType: false,
                processData: false
            });
        }
    });
</script>
    @stop