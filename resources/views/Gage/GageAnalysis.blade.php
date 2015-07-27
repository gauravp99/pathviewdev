@extends('GageApp')
@section('content')

    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.js"></script>
    <script src="js/app.js"></script>
    <script src="js/gageAnalysis.js"></script>
    <script src="js/app.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
    <link href="{{ asset('/css/bootstrap-switch.min.css') }}" rel="stylesheet">
    <script src="{{ asset('/js/bootstrap-switch.min.js') }}"></script>
    <div class="col-sm-12">
        @include('GageNavigation')
        <div class="conetent-header ">
            <p><b>GAGE Analysis</b></p>

            <div id="error-message"></div>
        </div>
        {!! form::open(array('url' => 'gageAnalysis','enctype' => 'multipart/form-data','method'=>'POST','files'=>true,'id' => 'gage_anal_form','name'=>'gage_anal_form')) !!}

    <div id="wrapper" class="col-md-8" ng-app="GageApp" ng-controller="analysisController">
        <div id="navigation" style="">
            <ul>
                <li class="selected">
                    <a href="#">Input / Output</a>
                </li>
                <li>
                    <a href="#">Analysis</a>
                </li>


            </ul>
        </div>
        <div id="steps">
        <?php
                //specifying default values for all the variables;
                $geneIdType = "entrez";
                $species = "hsa-Homo sapiens";
                $reference = "";
                $sample = "";
                $cutoff = "0.1";
                $setSizeMin = "10";
                $setSizeMax = "inf";
                $compare = "paired";
                $test2d = false;
                $rankTest = false;
                $useFold = true;
                $test = "gs.tTest";
                $dopathview = false;
                $dataType = "gene";
                ?>



            <fieldset class="step inputOutput-step">
                <div class="stepsdiv" id="assayData-div">
                    <div class="col-sm-12">

                        <div class="col-sm-5">
                            <a>
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            <label for="assayData">Assay Data:</label>
                        </div>

                        <div class="col-sm-7">


                            <div class="input-group">
                                <span style="color:red" ng-show="userForm.files.$dirty && userForm.files.$invalid"></span>
                <span class="input-group-btn">
                    <span class="btn btn-primary btn-file">
                        Browse&hellip; <input type="file" name="assayData" type="file"  id="assayData"  on-read-file="showContent($fileContent)">
                    </span>
                </span>
                                <input type="text" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                </div>
    @include('gageAnalysis')
@stop