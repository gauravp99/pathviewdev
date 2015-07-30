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
            <p><b>Example GAGE Analysis 1</b></p>

            <div id="error-message"></div>
        </div>
        {!! form::open(array('url' => 'exampleGageAnalysis1','method'=>'POST','files'=>true,'id' => 'gage_anal_form','name'=>'gage_anal_form')) !!}
        <div class="col-md-2">
            <div id="progress" class="col-md-12" hidden>
                <h2 class="alert alert-info"> Executing, Please wait. </h2>
                <img width="200px" hieght="200px" src="/images/load.gif">
            </div>
            <div id="completed" class="list-group col-md-12" hidden>
                <p> Completed Gage Analysis</p>
                <a id ='resultLink' href="/gageResult?analysis_id=" target="_blank">Click here to see results</a>
                <button id="backbutton" onclick="showWrapperForm()">Go Back</button>
            </div>
        </div>
        <div id="wrapper" class="col-md-8" ng-app="GageApp" ng-controller="ExampleAnalysisController1">
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
                $reference = "1,3";
                $sample = "2,4";
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
                                    <a href="/all/data/gagedata.txt" target="_blank">GageData.csv</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('gageAnalysis')
    <script>

        $(document).ready(function () {
            $("#geneSet option[value='sig.idx']")[0].setAttribute('selected', 'selected');
            $("#geneSet option[value='BP']")[0].setAttribute('disabled', 'disabled');
            $("#geneSet option[value='CC']")[0].setAttribute('disabled', 'disabled');
            $("#geneSet option[value='MF']")[0].setAttribute('disabled', 'disabled');
            $("#geneSet option[value='BP,CC,MF']")[0].setAttribute('disabled', 'disabled');
            $("#geneSet option[value='custom']")[0].setAttribute('disabled', 'disabled');


            $("#sampleselect option[value='2']")[0].setAttribute("selected", "selected");
            $("#sampleselect option[value='4']")[0].setAttribute("selected", "selected");
            $("#sampleselect option[value='1']")[0].setAttribute("disabled", "disabled");
            $("#sampleselect option[value='3']")[0].setAttribute("disabled", "disabled");


            $("#refselect option[value='1']")[0].setAttribute("selected", "selected");
            $("#refselect option[value='3']")[0].setAttribute("selected", "selected");
            $("#refselect option[value='2']")[0].setAttribute("disabled", "disabled");
            $("#refselect option[value='4']")[0].setAttribute("disabled", "disabled");
        });

    </script>

@stop