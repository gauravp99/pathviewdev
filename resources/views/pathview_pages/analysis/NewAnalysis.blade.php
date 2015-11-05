<?php
use Illuminate\Cacheche;
?>
@extends('app')

@section('content')

@include('navigation')

<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.js"></script>
<script src="js/PathviewApp.js"></script>
<script src="js/sliding.form.js"></script>

    <div class="col-sm-9">

        <div class="conetent-header ">

            <p><b>New Pathway Analysis</b></p>
        </div>

        <div class="col-sm-5">



            @if(Session::get('err')!=NULL)

                <ul class="alert alert-danger" style="font-size: 14px;list-style-type: none;">
                    <?php
                    for ($x = 0; $x < sizeof(Session::get('err')); $x++) {
                        echo "<li>" . Session::get('err')[$x] . "</li>";
                    }
                    ?>
                </ul>
            @endif
                <ul id="errors" hidden="true" class="alert alert-danger" style="font-size: 14px;list-style-type: none;"></ul>
        </div>
    </div>


    {{--Default/Inital values for the each attribute for the analysis--}}
        <?php
        $gfile = "";
        $cpdfile = "";
        $geneid = "ENTREZ";
        $cpdid = "KEGG";
        $species = "hsa-Homo sapiens";
        $pathway = "00640-Propanoate metabolism";
        $selectpath = "00010-Glycolysis / Gluconeogenesis";
        $pathidx = 0;
        $suffix = "pathview";
        $kegg = true;
        $layer = true;
        $split = false;
        $expand = false;
        $multistate = true;
        $matchd = true;
        $gdisc = false;
        $cdisc = false;
        $kpos = "topright";
        $pos = "bottomleft";
        $offset = "1.0";
        $align = "y";
        $glmt = 1;
        $gbins = 10;
        $glow = "green";
        $gmid = "grey";
        $ghigh = "red";
        $clmt = 1;
        $cbins = 10;
        $clow = "blue";
        $cmid = "grey";
        $chigh = "yellow";
        $nsum = "sum";
        $ncolor = "transparent";
        $compare = "paired";
        ?>

    {{--Div for Form for generating the analysis Form is devided into pages
    1. Input and Output
    2. Graphics
    3. Coloration --}}

    <div class="col-sm-7">
        <div id="content" ng-app="PathviewApp">
         <div id="wrapper" ng-controller="analysisController">
            <div id="navigation" style="display:none;">
                <ul>
                    <li class="selected">
                        <a href="#" id="inputOutput">Input & Output</a>
                    </li>
                    <li>
                        <a href="#" id="graphics">Graphics</a>
                    </li>
                    <li>
                        <a href="#" id="coloration">Coloration</a>
                    </li>

                </ul>
            </div>



            {{--Input and Output slider --}}
            <div id="steps">

                {{--Form Method: POST Action:postAnalysis with internally leads to AnalysisController Controller--}}

                {!! form::open(array('url' => 'postAnalysis','enctype' => 'multipart/form-data','method'=>'POST','files'=>true,'id' => 'anal_form')) !!}


                <fieldset class="step">
                    <input type="hidden" id="page_is_dirty" name="page_is_dirty" value="0"/>

                    <div class="stepsdiv" id="gfile-div" @if (isset(Session::get('err_atr')['gfile']))
                         style="background-color:#DA6666;" @endif>
                        <div class="col-sm-12">

                            <div class="col-sm-4">
                                <a href="tutorial#gene_data" onclick="window.open('tutorial#gene_data', 'newwindow', 'width=300, height=250').focus() ;return false;" title="Gene Data accepts data matrices in tab- or comma-delimited format (txt or csv)." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;margin-top:15px;"></span>
                                </a>
                                {!!form::label('gfile','Gene Data:',$attributes = array('style'=> 'margin-top:15px'))!!}
                            </div>

                            <div class="col-sm-8" >
                                <div class="col-sm-2" style="font-size: 18px;visibility:hidden;margin-top:10px;" id="edit">
                                <a href=""><span class="glyphicon glyphicon-edit" id="geneMenu" data-toggle="modal" data-target="#GenemyModal" ></span></a>
                                <a href=""><span class="glyphicon glyphicon-trash" id="clearFile" onclick="reset($('#assayData'))" ></span></a>
                                    </div>
                                <div class="col-sm-10">
                                    <!--//'data-toggle'=>"modal",'data-target'=>"#GenemyModal"-->
                            {!! form::file('gfile',$attributes = array('id' => 'assayData','on-read-file' => 'showGeneContent($fileContent,"gene")', 'ng-click'=>'delete()','style'=>'margin-top:10px;')) !!}
</div>
                                </div>
                            <div class="modal fade" id="GenemyModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel"
                                 aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title " id="ModalLabel">Gene data</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="col-sm-12">
                                                <div class="col-md-6">
                                                <a href="tutorial#control_reference" onclick="window.open('tutorial#gene_data', 'newwindow', 'width=300, height=250').focus(); return false;" title="The column numbers for controls"
                                                   target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">

                                                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"> Control/Refernce  </span>
                                                </a>
                                            </div>
                                            <div class="col-md-6">
                                                <a href="tutorial#control_sample" onclick="window.open('tutorial#gene_data', 'newwindow', 'width=300, height=250').focus(); return false;" title="The column numbers for cases"
                                                   target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">

                                                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"> Case/Sample </span>
                                                </a>
                                            </div>
                                                <div class="col-md-6">
                                                    <input type="text" id="geneColumns" name="geneColumns" style="width:100%;" ng-model="geneColumns" hidden="">
                                                    <input type="text" id="generef" name="generef" style="width:100%;" ng-model="geneRefSelect">
                                                    <select name="GeneRef[]" id="Generefselect"   multiple="" size="10" style="width:100%;" ng-model='geneRefSelect' ng-show="Genecolumns.length > 0">
                                                        <option ng-repeat="column in Genecolumns track by $index"
                                                                value="<% $index+1 %>">
                                                            <% column %>
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" id="genesam" name="genesam" style="width:100%;" ng-model="geneSamSelect">
                                                    <select name="GeneSam[]" id="Genesamselect"   multiple="" size="10" style="width:100%;" ng-model='geneSamSelect' ng-show="Genecolumns.length > 0">
                                                        <option ng-repeat="column in Genecolumns track by $index"
                                                                value="<% $index+1 %>">
                                                            <% column %>
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="col-sm-5">
                                                    <a href="tutorial#compare" onclick="window.open('gageTutorial#compare', 'newwindow', 'width=300, height=250').focus() ;return false;" title="Comparison scheme to be used." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                                        <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                                    </a>
                                                    <label for="compare">Compare:</label>
                                                </div>
                                                <div class="col-sm-7">
                                                    <p style="display: inline;margin-right:10px;">Paired</p> <input style="display: inline!important;" type="checkbox" id= "GeneCompare" name="genecompare" ng-model="GeneCompare" ng-init="checked=true" checked="">
                                                   <!-- <select  name="genecompare"  class="styled-select" id="compare" class="compare" ng-model="GeneCompare" >
                                                        <option value="paired" @if (strcmp($compare,'paired') == 0 ) selected @endif >paired</option>
                                                        <option value="unpaired" @if (strcmp($compare,'unpaired') == 0 ) selected @endif >unpaired</option>
                                                        <option value="1ongroup" @if (strcmp($compare,'1ongroup') == 0 ) selected @endif>1ongroup</option>
                                                        <option value="as.group" @if (strcmp($compare,'as.group') == 0 ) selected @endif >as.group</option>
                                                    </select> -->
                                                </div>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" id="123" class="btn btn-default" data-dismiss="modal" onclick="return saveGeneDynamicConentet()">Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input type="text"  name="NoOfColumns" value="<% Genecolumns.length %>" hidden="" id="NoOfColumns"   >
                        </div>
                    </div>


                    <div class="stepsdiv" id="cfile-div" @if (isset(Session::get('err_atr')['cfile'])) style="background-color:#DA6666;" @endif>
                        <div class="col-sm-12">
                            <div class="col-sm-4">
                                <a href="tutorial#cpd_data"  onclick="window.open('tutorial#cpd_data', 'newwindow', 'width=300, height=250').focus();return false;" id="cfile" title="Compound Data accepts data matrices in tab- or comma-delimited format (txt or csv)." target="_blank" class="scrollToTop" style="float:left;margin-top: 15px;">

                                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                </a>
                                {!!form::label('cpdfile','Compound Data:',$attributes = array('style'=> 'margin-top:15px;align:left'))!!}
                            </div>
                            <div class="col-sm-8" style="display: inline-block;">
                                <div class="col-sm-2" style="font-size: 18px;visibility:hidden;margin-top:10px;" id="cpdedit">
                                    <a href=""><span class="glyphicon glyphicon-edit" id="compoundMenu" data-toggle="modal" data-target="#myModal" ></span></a>
                                    <a href=""><span class="glyphicon glyphicon-trash" id="clearFile" onclick="reset($('#cpdassayData'))" ></span></a>
                                </div>
                                <div class="col-sm-10">
                                {!!form::file('cpdfile', $attributes = array('id' => 'cpdassayData','on-read-file' => 'showCompoundContent($fileContent,"compound")', 'ng-click'=>'delete()','style'=> 'margin-top:10px'))!!}
                            </div>
                                </div>
                        </div>

                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title " id="myModalLabel">Compound data</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="col-sm-12">
                                        <div class="col-md-6">
                                            <a href="tutorial#control_reference" onclick="window.open('tutorial#gene_data', 'newwindow', 'width=300, height=250').focus(); return false;" title="The column numbers for controls"
                                               target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">

                                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"> Control/Refernce  </span>
                                            </a>
                                        </div>
                                        <div class="col-md-6">
                                            <a href="tutorial#control_sample" onclick="window.open('tutorial#gene_data', 'newwindow', 'width=300, height=250').focus(); return false;" title="The column numbers for cases"
                                               target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">

                                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"> Case/Sample </span>
                                            </a>
                                        </div>
                                    <div class="col-md-6">
                                        <input type="text" id="cpdColumns" name="cpdColumns" ng-model="cpdColumns" hidden="">
                                        <input type="text" name="cpdref" id="cpdref" style="width:100%;" ng-model="cpdRefSelect">
                                        <select name="CompoundRef[]" id="Cpdrefselect"   multiple="" size="10" style="width:100%;" ng-model='cpdRefSelect' ng-show="Compoundcolumns.length > 0">
                                            <option ng-repeat="column in Compoundcolumns track by $index"
                                                    value="<% $index+1 %>">
                                                <% column %>
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="cpdsam" id="cpdsam" style="width:100%;" ng-model="cpdSamSelect">
                                        <select name="CompoundSam[]" id="Cpdsamselect"   multiple="" size="10" style="width:100%;" ng-model='cpdSamSelect' ng-show="Compoundcolumns.length > 0">
                                            <option ng-repeat="column in Compoundcolumns track by $index"
                                                    value="<% $index+1 %>">
                                                <% column %>
                                            </option>
                                        </select>
                                    </div>
                                        </div>
                                    <div class="col-sm-12">
                                        <div class="col-sm-5">
                                            <a href="tutorial#compare" onclick="window.open('gageTutorial#compare', 'newwindow', 'width=300, height=250').focus() ;return false;" title="Comparison scheme to be used." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                            </a>
                                            <label for="compare">Compare:</label>
                                        </div>
                                        <div class="col-sm-7">
                                            <p style="display: inline;margin-right:10px;">Paired</p> <input style="display: inline;" type="checkbox" id= "CpdCompare" name="cpdCompare" ng-model="CpdCompare" ng-init="checked=true" checked="true">

                                            <!--<select  name="cpdcompare"  class="styled-select" id="compare" class="compare" ng-model="CpdCompare" >
                                                <option value="paired" @if (strcmp($compare,'paired') == 0 ) selected @endif >paired</option>
                                                <option value="unpaired" @if (strcmp($compare,'unpaired') == 0 ) selected @endif >unpaired</option>
                                                <option value="1ongroup" @if (strcmp($compare,'1ongroup') == 0 ) selected @endif>1ongroup</option>
                                                <option value="as.group" @if (strcmp($compare,'as.group') == 0 ) selected @endif >as.group</option>
                                            </select> -->
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="123" class="btn btn-default" data-dismiss="modal" onclick="return saveCompoundDynamicConentet()">Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- form element to save the dynamically added select box and check box for files -->
                    <input type="text" id="geneFileDetails" value="" hidden="">
                    <input type="text" id="compoundFileDetails" value="" hidden="">

                @include('analysis')
    </div>
    </div>

    </div>

    <script type="text/javascript">
        $( document ).ready(function() {
            console.log( $('#compoundFileDetails').val() );
            console.log( $('#geneFileDetails').val() );
            geneFileDetails = $('#geneFileDetails').val();
            //gene data rendering
            if(geneFileDetails != null)
            {
                geneDataArray = geneFileDetails.split(";");
                if(geneDataArray[0] != null) {
                    if (geneDataArray[0].split(":")[1] != null) {
                        $('#edit').css("visibility", "visible");
                        selectOptionsList = geneDataArray[0].split(":")[1].split(",");
                        $.each(selectOptionsList, function (index, value) {
                            console.log(value);
                            $('#Genesamselect').append('<option value="' + (index + 1) + '">' + value + '</option>');
                            $('#Generefselect').append('<option value="' + (index + 1) + '">' + value + '</option>');
                        });
                        console.log($('#Genesamselect'));
                        console.log($('#Generefselect'));
                        $('#Genesamselect').removeAttr("ng-show");
                        $('#Generefselect').removeAttr("ng-show");
                        $('#Genesamselect').removeAttr("class");
                        $('#Generefselect').removeAttr("class");
                    }
                }

                if(geneDataArray[1] != null)
                {
                    if(geneDataArray[1].split(":")[1] != null  )
                    {

                            geneRefSelected = geneDataArray[1].split(":")[1];
                            geneRefSelectedArray = geneRefSelected.split(",");
                            console.log(geneRefSelectedArray);
                            $.each(geneRefSelectedArray, function(index, value){
                                console.log(value);
                                $("#Generefselect option[value='" + $.trim(value) + "']").attr("selected", 1);

                                $("#Genesamselect option[value='" + $.trim(value) + "']").attr("disabled", 1);
                            });
                            $('#generef').val(geneRefSelected);


                    }

                }


                if(geneDataArray[2] != null)
                {
                    if(geneDataArray[2].split(":")[1] != null)
                    {

                            geneSamSelected = geneDataArray[2].split(":")[1];
                            geneSamSelectedArray = geneSamSelected.split(",");
                            console.log(geneSamSelectedArray);
                            $.each(geneSamSelectedArray, function(index, value){
                                $("#Genesamselect option[value='" + $.trim(value) + "']").attr("selected", 1);
                                $("#Generefselect option[value='" + $.trim(value) + "']").attr("disabled", 1);
                            });
                            $('#genesam').val(geneSamSelected);

                    }

                }
                console.log(geneDataArray[geneDataArray.length-1]);
                if(geneDataArray[geneDataArray.length-1] != null)
                {
                    if(geneDataArray[geneDataArray.length-1] .split(":")[1] != null)
                    {
                        if(geneDataArray[geneDataArray.length-1].split(":")[1] == "true")
                        {
                                console.log("genecompare true");
                        }else{
                            $('#GeneCompare').removeAttr("checked");
                        }
                    }
                }

            }


            //compound data rendering
            compoundFileDetails = $('#compoundFileDetails').val();

            if(compoundFileDetails != null) {
            compoundDataArray = compoundFileDetails.split(";");

            //select fileds
            if(compoundDataArray[0] != null) {
                if(compoundDataArray[0].split(":")[1] != null) {
                    $('#cpdedit').css("visibility", "visible");
                    selectOptionsList = compoundDataArray[0].split(":")[1].split(",");

                    $.each(selectOptionsList, function (index, value) {
                        $('#Cpdrefselect').append('<option value="' + (index + 1) + '">' + value + '</option>');
                        $('#Cpdsamselect').append('<option value="' + (index + 1) + '">' + value + '</option>');
                    });
                    console.log($('#Cpdrefselect'));
                    console.log($('#Cpdsamselect'));
                    $('#Cpdrefselect').removeAttr("ng-show");
                    $('#Cpdsamselect').removeAttr("ng-show");
                    $('#Cpdrefselect').removeAttr("class");
                    $('#Cpdsamselect').removeAttr("class");
                }
                }

                if(compoundDataArray[1] != null)
                {
                    if(compoundDataArray[1].split(":")[1] != null)
                    {
                        compoundRefSelected = compoundDataArray[1].split(":")[1];
                        compoundRefSelectedArray = compoundRefSelected.split(",");
                        console.log(compoundRefSelectedArray);
                        $.each(compoundRefSelectedArray, function(index, value){
                            console.log(value);
                            $("#Cpdrefselect option[value='" + $.trim(value) + "']").attr("selected", 1);

                            $("#Cpdesamselect option[value='" + $.trim(value) + "']").attr("disabled", 1);
                        });
                        $('#cpdref').val(compoundRefSelected);
                    }

                }


                if(compoundDataArray[2] != null)
                {
                    if(compoundDataArray[2].split(":")[1] != null)
                    {
                        compoundSamSelected = compoundDataArray[2].split(":")[1];
                        compoundSamSelectedArray = compoundSamSelected.split(",");
                        console.log(compoundSamSelectedArray);
                        $.each(compoundSamSelectedArray, function(index, value){
                            $("#Cpdsamselect option[value='" + $.trim(value) + "']").attr("selected", 1);
                            $("#Cpdrefselect option[value='" + $.trim(value) + "']").attr("disabled", 1);
                        });
                        $('#cpdsam').val(compoundSamSelected);
                    }

                }

            }



            $('#compoundFileDetails').val("");
            $('#geneFileDetails').val("");


        });
        var savedCompoundDynamicText = "";
        var savedGeneDynamicText = "";
        function saveCompoundDynamicConentet() {
            if(savedCompoundDynamicText.indexOf("cpdSelect") != -1)
            {
                savedCompoundDynamicText = "";
                saveGeneDynamicConentet();
            }
            var cpdSelect = $('#Cpdrefselect Option').map(function(){return $(this).text()}).get().join(", ");
            var cpdRefSelect = $('select#Cpdrefselect').val();
            var cpdSamSelect = $('select#Cpdsamselect').val();
            var cpdPaired = $('#CpdCompare').is(":checked");

            savedCompoundDynamicText +="cpdSelect:"+cpdSelect+";"
            if(cpdRefSelect != null)
                savedCompoundDynamicText +="cpdRefSelect:"+cpdRefSelect.join(", ")+";"
            if(cpdSamSelect != null)
                savedCompoundDynamicText +="cpdSamSelect:"+cpdSamSelect.join(", ")+";"
            savedCompoundDynamicText +="cpdCompare:"+$('#CpdCompare').is(':checked')+";";
            console.log(savedCompoundDynamicText.replace(/[\t\n\s]+/g,''));
            $('#compoundFileDetails').val(savedCompoundDynamicText.replace(/[\t\n\s]+/g,''));
        }

        function saveGeneDynamicConentet(){

            if(savedGeneDynamicText.indexOf("geneSelect") != -1)
            {
                savedGeneDynamicText = "";

            }

            var geneSelect = $('#Generefselect Option').map(function(){return $(this).text()}).get().join(", ");
            var geneRefSelect = $('select#Generefselect').val();
            var geneSamSelect = $('select#Genesamselect').val();
            var genePaired = $('#GeneCompare').is(":checked");


            savedGeneDynamicText +="geneSelect:"+geneSelect+";"

            if(geneRefSelect != null)
            savedGeneDynamicText +="geneRefSelect:"+geneRefSelect.join(", ")+";"
            if(geneSamSelect != null)
            savedGeneDynamicText +="geneSamSelect:"+geneSamSelect.join(", ")+";"
            savedGeneDynamicText +="geneCompare:"+$('#GeneCompare').is(':checked')+";";

            console.log(savedGeneDynamicText.replace(/[\t\n\s]+/g,' '));
            $('#geneFileDetails').val(savedGeneDynamicText.replace(/[\t\n\s]+/g,' '));

        }
        var pathway_array = [];
        var species_array = [];
        var gene_array = [];
        var cmpd_array = [];
        <?php
          $pathway = Cache::remember('Pathway_id', 10, function()
                {
                    return DB::table('pathway')->get(array('pathway_id'));
                });
                if (Cache::has('Pathway_id'))
                {
                    $pathway = Cache::get('Pathway_id');
                }
        ?>
        <?php
            $species = Cache::remember('Species_id', 10, function()
                {
                    return DB::table('species')->get(array('species_id'));
                });
                if (Cache::has('Species_id'))
                {
                    $species = Cache::get('Species_id');
                }
        ?>
        <?php
         $gene = Cache::remember('gene_id', 10, function()
                {
                    return DB::table('gene')->get(array('gene_id'));;
                });
                if (Cache::has('gene_id'))
                {
                    $gene = Cache::get('gene_id');
                }
         $gene = DB::table('gene')->get(array('gene_id'));?>
        <?php
        $cmpd = Cache::remember('compound_id', 10, function()
                {
                    return DB::table('compoundID')->get(array('compound_id'));;
                });
                if (Cache::has('compound_id'))
                {
                    $cmpd = Cache::get('compound_id');
                }
            ?>
        pathway_array = <?php echo JSON_encode($pathway);?>;
        species_array = <?php echo JSON_encode($species);?>;
        gene_array = <?php echo JSON_encode($gene);?>;
        cmpd_array = <?php echo JSON_encode($cmpd);?>;




        $(document).ready(function() {
            console.log("in document ready");
            console.log("in document ready");
            $('#Generefselect').change(function () {
                console.log("in generefselect function");
                var ref_selected_text = "";

                var noOfColumns = $('#geneColumns').val() ;

                console.log("noofcolumns: is working"+noOfColumns);
                for (var j = 1; j < noOfColumns; j++) {

                    $("#Genesamselect option[value=\"" + j + "\"]")[0].removeAttribute('disabled');
                }
                console.log($(this).val());
                for (var i = 0; i < $(this).val().length; i++) {
                    var selected = $(this).val()[i];

                    $("#Genesamselect option[value=\"" + selected + "\"]")[0].setAttribute('disabled', 'disabled');
                }

            });

            $('#Genesamselect').change(function () {

                var noOfColumns = $('#geneColumns').val();
                var sample_selected_text = "";
                console.log("noofcolumns: is working"+noOfColumns);
                for (var j = 1; j < noOfColumns; j++) {
                    sample_selected_text = "";
                    $("#Generefselect option[value=\"" + j + "\"]")[0].removeAttribute('disabled');
                }
                for (var i = 0; i < $(this).val().length; i++) {

                    var selected = $(this).val()[i];
                    console.log(selected);
                    sample_selected_text = sample_selected_text + selected + ",";
                    $("#Generefselect option[value=\"" + selected + "\"]")[0].setAttribute('disabled', 'disabled');
                }


            });

            $('#Cpdrefselect').change(function () {
                console.log("in generefselect function");
                var ref_selected_text = "";
                var noOfColumns = $('#cpdColumns').val() ;
                console.log("noofcolumns: is working"+noOfColumns);
                for (var j = 1; j < noOfColumns; j++) {
                    ref_selected_text = "";
                    $("#Cpdsamselect option[value=\"" + j + "\"]")[0].removeAttribute('disabled');
                }
                for (var i = 0; i < $(this).val().length; i++) {

                    var selected = $(this).val()[i];
                    console.log(selected);
                    ref_selected_text = ref_selected_text + (selected) + ",";
                    $("#Cpdsamselect option[value=\"" + selected + "\"]")[0].setAttribute('disabled', 'disabled');
                }

            });

            $('#Cpdsamselect').change(function () {

                var noOfColumns = $('#cpdColumns').val();
                console.log("noofcolumns: is working"+noOfColumns);
                var sample_selected_text = "";
                for (var j = 1; j < noOfColumns; j++) {
                    sample_selected_text = "";
                    $("#Cpdrefselect option[value=\"" + j + "\"]")[0].removeAttribute('disabled');
                }
                for (var i = 0; i < $(this).val().length; i++) {

                    var selected = $(this).val()[i];
                    console.log(selected);
                    sample_selected_text = sample_selected_text + selected + ",";
                    $("#Cpdrefselect option[value=\"" + selected + "\"]")[0].setAttribute('disabled', 'disabled');
                }


            });


        });


        $("#assayData").change(function(){
            var fileName = $("#assayData").val();
            if(fileName)
            {
                $("#Genesamselect option").prop("disabled", false);
                $("#Generefselect option").prop("disabled", false);
                $('#geneMenu').trigger('click');

                console.log("in re file select here");

            }


        });
        $("#cpdassayData").change(function(){
            var fileName1 = $("#cpdassayData").val();
            if(fileName1){
                $("#Cpdsamselect option").prop("disabled", false);
                $("#Cpdrefselect option").prop("disabled", false);
                $('#compoundMenu').trigger('click');
            }





        });



        document.getElementById('assayData').addEventListener('change', myMethod, false);

        function myMethod(evt) {
            var files = evt.target.files;
            f= files[0];
            if (f==undefined) {
                document.getElementById('123').click();
            }

                };

    </script>
<script src="{{asset('/js/analysis.js')}}"></script>

    <script src="{{asset('/js/newanalysis.js')}}"></script>
    <script>

        window.reset = function (e) {
            console.log("in reset function");
            e.wrap('<form>').closest('form').get(0).reset();
            e.unwrap();
        }

    </script>
@stop

