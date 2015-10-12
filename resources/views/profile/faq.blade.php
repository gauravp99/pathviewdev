@extends('app')

@section('content')
    @include('navigation')
    <script src="/bower_components/jquery/dist/jquery.js"></script>
    <script src="/bower_components/angular/angular.js"></script>
    <script src="/js/faq.js"></script>
    <script src="/js/commentService.js"></script>
    <div ng-app="faqApp"  class="col-sm-8" >
        <div ng-controller="faqController">
    <div class="page-header">
        <h1>Frequently Asked Question</h1>
        <h4>Submit your issues here. before submitting find all the comments categorised with category</h4>
    </div>


    <p class="text-center" ng-show="loading"><span class="fa fa-meh-o fa-5x fa-spin"></span></p>


    <div class="comment" ng-hide="loading" ng-repeat="comment in comments">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3><% comment.text_string %> <small>by <% comment.author  %></h3>
                </div>
                <div class="panel-footer">
                    <p id="comment1">Comment #<% comment.id  %></p>
                    <nav style="alignment: right"><p id="comment"><% comment.created_at  %></p></nav>
                    <div cstyle="display: inline;">
                        <div class="col-lg-offset-2 col-md-8 reply" hidden="" id="<% comment.id %>" style="display:inline-block;font-size:small;-ms-box-sizing:content-box;
-moz-box-sizing:content-box;
-webkit-box-sizing:content-box;
box-sizing:content-box;border:thick;border-color: #105cb6;padding: 10px;" >
                </div>
            </div>



            </div>
        </div>
    </div>

    <hr/>


        </div>
            <div class="col-sm-12" style="margin-top: 60px;">
                <form ng-submit="submitComment()"> <!-- ng-submit will disable the default form action and use our function -->
                    <h2>Ask any issue with application ?</h2>
                    <!-- AUTHOR -->
                    <div class="form-group">
                        <input type="text" class="form-control input-sm" name="author" ng-model="commentData.author" placeholder="Name" required>
                    </div>

                    <!-- COMMENT TEXT -->
                    <div class="form-group">
                        <input type="text" class="form-control input-lg" name="comment" ng-model="commentData.text" placeholder="Say what you have to say" required>

                    </div>


                    <!-- SUBMIT BUTTON -->
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                    </div>
                </form>
            </div>
</div>
<script>
    $('.reply').hide();


</script>





    @stop