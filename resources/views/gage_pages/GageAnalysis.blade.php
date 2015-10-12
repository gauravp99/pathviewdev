@extends('GageApp')

@section('content')

    @include('GageNavigation')

    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.js"></script>
    <script src="js/app.js"></script>
    <script src="js/gageAnalysis.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
    <link href="{{ asset('/css/bootstrap-switch.min.css') }}" rel="stylesheet">
    <script src="{{ asset('/js/bootstrap-switch.min.js') }}"></script>




    <div class="col-md-9">
        <div class="conetent-header ">
            <p><b>GAGE Analysis</b></p>
        </div>
        <div id="error-message"></div>
        <!-- <div class="col-md-2">
  <div id="progress" class="col-md-12" hidden>
      <h2 class="alert alert-info"> Executing, Please wait. </h2>
      <img width="200px" hieght="200px" src="/images/load.gif">
  </div>
  <div id="completed" class="list-group col-md-12" hidden>
      <p> Completed gage_pages Analysis</p>
      <a id='resultLink' href="/gageResult?analysis_id=" target="_blank">Click here to see results</a>
      <button id="backbutton" onclick="showWrapperForm()">Go Back</button>
  </div>
</div>-->
    </div>
    <?php
    //specifying default values for all the variables;
    $geneIdType = "entrez";
    $species = "hsa-Homosapiens-human";

    $reference = "";
    $sample = "";
    $cutoff = "0.1";
    $setSizeMin = "10";
    $setSizeMax = "infinite";
    $compare = "paired";
    $test2d = false;
    $rankTest = false;
    $useFold = true;
    $test = "gs.tTest";
    $dopathview = false;
    $dataType = "gene";
    ?>


    <input type="text" id="rememberTxt" hidden="">


    <div class="col-md-7">

        <div id="content">
            <div id="wrapper" ng-app="GageApp" ng-controller="analysisController">

                <div id="navigation" style="display:none;">

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
                    {!! form::open(array('url' => 'gageAnalysis','enctype' =>'multipart/form-data','method'=>'POST','files'=>true,'id' => 'gage_anal_form','name'=>'gage_anal_form')) !!}
                    <fieldset class="step inputOutput-step">
                        <div class="stepsdiv" id="assayData-div">
                            <div class="col-sm-12">

                                <div class="col-sm-5">
                                    <a href="gageTutorial#assay_data"
                                       onclick="window.open('gageTutorial#assay_data', 'newwindow', 'width=300, height=250').focus() ;return false;"
                                       title="Input data containing an expression matrix or matrix-like data structure, with genes as rows and samples as columns. Accepts only CSV and TXT as extension."
                                       target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                        <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                    </a>
                                    <label for="assayData">Assay Data:</label>
                                </div>

                                <div class="col-sm-7">


                                    <div class="input-group">
                                    <span style="color:red"
                                          ng-show="userForm.files.$dirty && userForm.files.$invalid"></span>
                <span class="input-group-btn">
                    <span class="btn btn-primary btn-file">
                        Browse&hellip; <input type="file" name="assayData" type="file" id="assayData"
                                              on-read-file="showContent($fileContent)" ng-click="delete()">
                    </span>
                </span>
                                        <input type="text" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @include('gageAnalysis')
                        <script>
                            $(window).bind('beforeunload', function () {
                                var geneIdSelected = $("#geneIdType").val();
                                var species = $("#species").val();
                                var geneSetSelected = $('#geneSet').val();
                                var referenceSelected = $('#refselect').val();
                                var sampleSelected = $('#sampleselect').val();
                                var dataTypeSelected = $('#dataType').val();
                                var usePathview = $('#usePathview').is(":checked");
                                var columns = [];
                                $("#refselect option").each(function () {
                                    columns.push($(this).text()); // Add $(this).val() to your list
                                });


                                //adding the dynamically added text hidden input variable

                                $val = 'col:';
                                if (columns != null || columns != undefined) {
                                    $.each(columns, function (index, value) {
                                        value = value.replace(/(\r\n|\n|\r|\s)/gm, "");
                                        $val += value;
                                        $val += ',';
                                    });
                                }
                                $val += ';';

                                $val += 'git:';
                                $("#geneIdType > option").each(function() {

                                    $val += this.value+",";
                                });
                                $val +=';';

                                $val += 'ref:';
                                if (referenceSelected != null || referenceSelected != undefined) {
                                    $.each(referenceSelected, function (index, value) {
                                        value = value.replace(/(\r\n|\n|\r|\s)/gm, "");
                                        $val += value;
                                        $val += ',';
                                    });
                                }
                                $val += ';';

                                $val += 'sam:';

                                if (sampleSelected != null || sampleSelected != undefined) {
                                    $.each(sampleSelected, function (index, value) {
                                        value = value.replace(/(\r\n|\n|\r|\s)/gm, "");
                                        $val += value;
                                        $val += ',';
                                    });
                                }
                                $val += ';';

                                $val += 'gen:';
                                if (geneSetSelected != null || geneSetSelected != undefined) {
                                    $.each(geneSetSelected, function (index, value) {
                                        value = value.replace(/(\r\n|\n|\r|\s)/gm, "");
                                        $val += value;
                                        $val += ',';
                                    });
                                }
                                $val += ';';

                                $val += 'gid:' + geneIdSelected + ';';

                                if ($('#geneIdType > option').length == 1 && geneIdSelected == 'custom') {
                                    $val += 'gty:custom;';
                                }
                                else if ($('#geneIdType > option').length == 1) {
                                    $val += 'gty:go;';

                                }
                                else {
                                    $val += 'gty:kegg;';
                                }
                                if (usePathview)
                                    $val += 'pat:true;';
                                else
                                    $val += 'pat:false;';
                                $val += 'dat:' + dataTypeSelected + ';';

                                $val += 'spe:' + species + ';';

                                $('#rememberTxt').val($val);
                            });


                            $(function () {
                                referenceText = "";
                                sampleText = "";
                                var isOpera = !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
                                // Opera 8.0+ (UA detection to detect Blink/v8-powered Opera)
                                var isFirefox = typeof InstallTrigger !== 'undefined';   // Firefox 1.0+
                                var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
                                // At least Safari 3+: "[object HTMLElementConstructor]"
                                var isChrome = !!window.chrome && !isOpera;              // Chrome 1+
                                var isIE = /*@cc_on!@*/false || !!document.documentMode;   // At least IE6

                                if (isChrome || isIE || isSafari || isFirefox) {
                                    var each = $('#rememberTxt').val().split(';').slice(0);

                                    $.each(each, function (index, value) {
                                        if (value.substr(0, 3) === 'col') {
                                            columns = value.substr(4);
                                        }
                                        else if (value.substr(0, 3) === 'ref') {
                                            referenceText = value.substr(4);
                                        }

                                        else if (value.substr(0, 3) === 'sam') {
                                            sampleText = value.substr(4);
                                        }

                                        else if (value.substr(0, 3) === 'gen') {
                                            geneSet = value.substr(4);
                                        }
                                        else if (value.substr(0, 3) === 'gid') {
                                            geneid = value.substr(4);
                                        }
                                        else if(value.substr(0,3) === 'git')   {

                                            gidTotal = value.substr(4).split(',');
                                        }
                                        else if (value.substr(0, 3) === 'pat') {
                                            usePathview = value.substr(4);
                                        }

                                        else if (value.substr(0, 3) === 'dat') {
                                            dataTypeSelected = value.substr(4);
                                        }
                                        else if (value.substr(0, 3) === 'spe') {
                                            species = value.substr(4);
                                            $('#species').val(species);
                                        }
                                        else if (value.substr(0, 3) === 'gty') {
                                            geneIDType = value.substr(4);
                                            if (geneIDType !== 'kegg') {
                                                $('#specieslist').empty();
                                                $.each(goSpeciesArray, function (index, xyz) {
                                                    $('#specieslist').append($("<option></option>")
                                                            .attr("value", goSpeciesArray[index]['species_id'] + '-' + goSpeciesArray[index]['species_desc'] + '-' + goSpeciesArray[index]['Go_name']).text(goSpeciesArray[index]['species_id'] + '-' + goSpeciesArray[index]['species_desc'] + '-' + goSpeciesArray[index]['Go_name']));
                                                });
                                                if (geneIDType === 'go') {
                                                    $.each(goSpecIdBind, function (key1, value1) {
                                                        $('#specieslist').append($("<option></option>")
                                                                .attr("value", key1).text(key1));
                                                    });




                                                }
                                                else {
                                                    $('#specieslist').empty();
                                                    $('#specieslist').append($("<option></option>")
                                                            .attr("value", "custom").text("custom"));
                                                }
                                            }

                                        }
                                    });

                                    if ($('#rememberTxt').val() !== '') {
                                        $colum = columns.split(',').slice(0);
                                        $colum.splice(($colum).length, 1);
                                        $colum.splice(($colum).length - 1, 1);
                                        if($colum.length > 0)
                                        {
                                            $('#sampleselect').attr('class', 'dynamicshow');
                                            $('#refselect').attr('class', 'dynamicshow');
                                            $('#sampleselect').show();
                                            $('#refselect').show();
                                        }
                                        $.each($colum, function (index, value) {
                                            $('#sampleselect').append($("<option></option>")
                                                    .attr("value", index + 1).text(value));

                                            $('#refselect').append($("<option></option>")
                                                    .attr("value", index + 1).text(value));

                                        });
                                        $.each($colum, function (index, value) {
                                            $('#refselect option[value=' + (index + 1) + ']').attr('class', 'tempColumn');

                                            $('#sampleselect option[value=' + (index + 1) + ']').attr('class', 'tempColumn');

                                        });

                                        if(referenceText !== '') {
                                            var refArray = referenceText.split(',').splice(0);
                                            refArray.splice((refArray).length, 1);
                                            refArray.splice((refArray).length - 1, 1);
                                            $.each(refArray, function (index, value) {
                                                $('#refselect option[value=' + value + ']').attr('selected', 'selected');
                                            });
                                        }
                                        if(sampleText !== '') {
                                            var sampleArray = sampleText.split(',').splice(0);
                                            sampleArray.splice((sampleArray).length, 1);
                                            sampleArray.splice((sampleArray).length - 1, 1);
                                            $.each(sampleArray, function (index, value) {
                                                $('#sampleselect option[value=' + value + ']').attr('selected', 'selected');
                                            });
                                        }

                                        $('#NoOfColumns').val(($colum).length - 1);

                                        if (geneid === 'custom') {
                                            $('#geneIdType').append($("<option></option>").attr("value", geneid).text(geneid));
                                            $('#geneIdFile').show();
                                        }
                                        else if (geneid !== 'entrez' && geneid !== 'kegg') {
                                            $('#geneIdType').empty();
                                            $.each(gidTotal,function(index,value){
                                                $('#geneIdType').append($("<option></option>").attr("value", value).text(value));
                                            });

                                            $('#geneIdType option[value='+geneid+']')[0].setAttribute('selected','selected');
                                        }

                                        if (usePathview == 'true') {
                                            $('#dataType-div').show();
                                        }
                                    }
                                }
                            });

                        </script>

@stop

