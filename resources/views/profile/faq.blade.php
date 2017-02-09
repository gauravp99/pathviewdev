@extends('app')

@section('content')
    @include('navigation')
    <script src="/bower_components/jquery/dist/jquery.js"></script>
    <script src="/bower_components/angular/angular.js"></script>
    <script src="/js/faq.js"></script>
    <script src="/js/commentService.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <div ng-app="faqApp" class="col-sm-8">
        <div ng-controller="faqController">
            <div class="page-header">
                <h1>Questions and Answers</h1>
                <h4></h4>
            </div>


            <p class="text-center" ng-show="loading"></p>
            <button type="button" class="btn btn-default navbar-btn" id="hideComments">Show previously Asked Questions
                <span class="glyphicon glyphicon-resize-full"></span></button>
            <div class="comment" id="hidebar" hidden="">
                <div class="comment" ng-hide="loading" ng-repeat="comment in comments">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
			    <h4><pre><small><% comment.author  %>: </small> <% comment.text_string %> <small> <% comment.created_at  %> </small> </pre></h4>
                              <div class="comment" ng-hide="loading" ng-repeat="comReply in commentReply" ng-if="comReply.comment_id==comment.id">
			        <h4><pre><small>Admin: </small> <% comReply.text_string %> <small> <% comment.created_at  %> </small> </pre></h4>
                              </div>
                            </div>
                        </div>
                    </div>

                    <hr/>


                </div>
            </div>
            <div class="col-sm-12" style="margin-top: 60px;font-size: 14px;">
                <h3>Ask ?</h3>

                <form class="form-horizontal" id="form-contact" role="form" ng-submit="submitComment()" method="post"
                      accept-charset="UTF-8" enctype="multipart/form-data" action="/postMessage">
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Name</label>

                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" name="name" ng-model="commentData.author"
                                   required placeholder="First & Last Name" ng-required="required"
                                   value= @if(Auth::user())  {{Auth::user()->name}} @endif>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-sm-3 control-label">Email</label>

                        <div class="col-sm-9">

                            <input type="email" class="form-control" id="email" name="email" ng-required="required"
                                   placeholder="example@domain.com" required
                                   value= @if(Auth::user())  {{Auth::user()->email}} @endif >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message" class="col-sm-3 control-label">Your Question</label>

                        <div class="col-sm-9">
                            <pre><textarea class="form-control" rows="4" ng-model="commentData.text" name="message"
                                      required=""></textarea></pre>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-3">
                            <input id="submit" name="submit" style="width:220px;font-size: 20px;" type="submit"
                                   value="Send" class="btn btn-primary">
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
        <script>
            $(document).ready(function () {
                $('#hideComments').click(function () {
                    if ($('#hideComments').text() === "Hide Previosly Asked Questions") {
                        $('#hidebar').hide();
                        $('#hideComments').text("Show Previously Asked Questions");
                    } else {
                        $('#hidebar').show();
                        $('#hideComments').text("Hide Previosly Asked Questions");
                    }

                });
            });
            $(document).on('click', '.browse', function () {
                var file = $(this).parent().parent().parent().find('.file');
                file.trigger('click');
            });
            $(document).on('change', '.file', function () {
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

                        extension: "jpg|jpeg|png"
                    },
                    message: {},
                    name: {},
                    email: {

                        email: true
                    }
                },
                messages: {},
                submitHandler: function (form) {
                    var fd = new FormData(document.querySelector("form"));
                    $.ajax({
                        url: $('#form-contact').attr('action'),
                        type: $('#form-contact').attr('method'),
                        data: fd,
                        success: function (data) {
                            console.log(data);
                            $('#form-contact').trigger("reset");
                        },
                        failure: function (data) {
                            console.log(data);
                        },
                        contentType: false,
                        processData: false
                    });
                }
            });
            $('.reply').hide();
        </script>


@stop
