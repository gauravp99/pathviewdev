<?php
use Illuminate\Cacheache;
?>
@extends('app')

@section('content')

    @include('navigation')
    <script src="js/sliding.form.js"></script>

    <script src="js/PathviewApp.js"></script>
    <div class="col-sm-9">
        <div class="conetent-header">


    <p><b>Example Analysis 2: Multiple Sample Graphviz View</b></p>

        </div>
        <div class="col-sm-5">

            @if ($errors->any())
                <ul class="alert alert-danger" style="font-size: 14px;list-style-type: none;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif


            @if(Session::get('err')!=NULL)

                <ul class="alert alert-danger" style="font-size: 14px;list-style-type: none;">
                    <?php
                    for ($x = 0; $x < sizeof(Session::get('err')); $x++) {
                        echo "<li>" . Session::get('err')[$x] . "</li>";
                    }
                    ?>
                </ul>

            @endif
            <ul id="errors" hidden="true" class="alert alert-danger" style="font-size: 14px;list-style-type: none;">

            </ul>
        </div>
    </div>


    <?php
    $gfile = "gse16873.3.txt";
    $cpdfile = "sim.cpd.data1.csv";
    $geneid = "ENTREZ";
    $cpdid = "KEGG";
    $species = "hsa-Homo sapiens";
    $pathway = "00010-Glycolysis / Gluconeogenesis";
    $selectpath = "00640-Propanoate metabolism,";
    $pathidx = 0;
    $suffix = "multi";
    $kegg = false;
    $layer = true;
    $split = false;
    $expand = false;
    $multistate = true;
    $matchd = false;
    $gdisc = false;
    $cdisc = false;
    $kpos = "topright";
    $pos = "bottomleft";
    $offset = "1.0";
    $align = "y";
    $glmt = "-1,2";
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
        <div id="wrapper">
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

            <div id="steps" ng-controller="example2Controller">

                {{--Form Method: POST Action:post_exampleAnalusis2 with internally leads to AnalysisController Controller--}}

                {!! form::open(array('url' => 'post_exampleAnalysis2','method'=>'POST','files'=>true,'id' =>
                'anal_form')) !!}

                <fieldset class="step">
                    <input type="hidden" id="page_is_dirty" name="page_is_dirty" value="0"/>

                    <div class="stepsdiv" id="gfile-div" @if (isset(Session::get('err_atr')['gfile']))
                         style="background-color:#DA6666;" @endif>
                        <div class="col-sm-12">
                            <div class="col-sm-5">
                                <a href="tutorial#gene_data" onclick="window.open('tutorial#gene_data', 'newwindow', 'width=300, height=250').focus(); return false;" title="Gene Data accepts data matrices in tab- or comma-delimited format (txt or csv)."
                                   target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">

                                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"> </span>
                                </a>
                                {!!form::label('gcheck','Gene Data:')!!}
                            </div>
                            <div class="col-sm-7">

                                <span class="glyphicon glyphicon-edit" id="geneMenu" data-toggle="modal" data-target="#GenemyModal" ></span>

                                <input name="gcheck" id="gcheck" value="T" type="checkbox"
                                <?php if (isset(Session::get('Sess')['gcheck'])) {
                                    echo "checked";
                                } else {
                                    if (Session::get('Sess') == NULL) {
                                        echo "checked";
                                    }
                                }?>>


                                <a href="/data/gse16873.3.txt" target="_blank">{{$gfile}}</a>

                            </div>
                        </div>
                    </div>
                    <br/>
                    <br/>

                    <div class="stepsdiv" id="cfile-div" @if (isset(Session::get('err_atr')['cfile']))
                         style="background-color:#DA6666;" @endif>
                        <div class="col-sm-12">
                            <div class="col-sm-5">
                                <a href="tutorial#cpd_data" onclick="window.open('tutorial#cpd_data', 'newwindow', 'width=300, height=250').focus(); return false;" title="Compound Data accepts data matrices in tab- or comma-delimited format (txt or csv)."
                                   target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">

                                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span></a>
                                {!!form::label('cpdcheck','Compound Data:')!!}
                            </div>
                            <div class="col-sm-7">

                                <span class="glyphicon glyphicon-edit" id="geneMenu" data-toggle="modal" data-target="#myModal" ></span>

                                <input name="cpdcheck" id="cpdcheck" value="T" type="checkbox"
                                <?php if (isset(Session::get('Sess')['cpdcheck'])) {
                                    echo "checked";
                                } else {
                                    if (Session::get('Sess') == NULL) {
                                        echo "checked";
                                    }
                                }?>>
                                <a href="/data/sim.cpd.data1.csv" target="_blank">{{$cpdfile}}</a>
                            </div>
                        </div>


                    </div>
                    <div class="modal fade" id="GenemyModal" tabindex="-1" role="dialog" aria-labelledby="ModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span></button>
                                    <h4 class="modal-title " id="ModalLabel">Gene data</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="col-sm-12">
                                        <div class="col-md-6">
                                            <a href="tutorial#control_reference" onclick="window.open('tutorial#gene_data', 'newwindow', 'width=300, height=250').focus(); return false;" title="The column numbers for controls"
                                               target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">

                                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"> </span>
                                            </a><label>Control/Refernce</label>
                                        </div>
                                        <div class="col-md-6">
                                            <a href="tutorial#control_sample" onclick="window.open('tutorial#gene_data', 'newwindow', 'width=300, height=250').focus(); return false;" title="The column numbers for cases"
                                               target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">

                                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"> </span>
                                            </a><label>Case/Sample</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" id="geneColumns" name="geneColumns" style="width:100%;" ng-model="geneColumns" hidden="" class="ng-pristine ng-untouched ng-valid">
                                            <input type="text" id="generef" name="generef" style="width:100%;" ng-model="geneRefSelect" class="ng-pristine ng-untouched ng-valid">
                                            <select name="GeneRef[]" id="Generefselect" multiple="" size="10" style="width:100%;" ng-model="geneRefSelect"  class="ng-pristine ng-valid ng-touched">
                                                <option  value="1">
                                                    HN_1
                                                </option><option  value="2">
                                                    DCIS_1
                                                </option><option  value="3">
                                                    HN_2
                                                </option><option  value="4">
                                                    DCIS_2
                                                </option><option  value="5">
                                                    HN_3
                                                </option><option  value="6">
                                                    DCIS_3
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" id="genesam" name="genesam" style="width:100%;" ng-model="geneSamSelect" class="ng-pristine ng-untouched ng-valid">
                                            <select name="GeneSam[]" id="Genesamselect" multiple="" size="10" style="width:100%;" ng-model="geneSamSelect"  class="ng-pristine ng-untouched ng-valid">
                                                <option  value="1">
                                                    HN_1
                                                </option><option  value="2">
                                                    DCIS_1
                                                </option><option  value="3">
                                                    HN_2
                                                </option><option  value="4">
                                                    DCIS_2
                                                </option><option  value="5">
                                                    HN_3
                                                </option><option  value="6">
                                                    DCIS_3
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
                                            <p style="display: inline;margin-right:10px;">Paired</p> <input style="display: inline!important;" type="checkbox" id="GeneCompare" name="genecompare" ng-model="GeneCompare" ng-init="checked=true" checked="" class="ng-pristine ng-untouched ng-valid">
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


                    <!-- Modal -->
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span></button>
                                    <h4 class="modal-title " id="myModalLabel">Compound data</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="col-sm-12">
                                        <div class="col-md-6">
                                            <a href="tutorial#control_reference" onclick="window.open('tutorial#gene_data', 'newwindow', 'width=300, height=250').focus(); return false;" title="The column numbers for controls"
                                               target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">

                                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                            </a><label>Control/Refernce</label>

                                        </div>
                                        <div class="col-md-6">
                                            <a href="tutorial#control_sample" onclick="window.open('tutorial#gene_data', 'newwindow', 'width=300, height=250').focus(); return false;" title="The column numbers for cases"
                                               target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">

                                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;">  </span>
                                            </a><label>Case/Sample</label>
                                        </div>

                                        <div class="col-md-6">

                                            <input type="text" id="cpdColumns" style="width:80%;" name="cpdColumns" ng-model="cpdColumns" hidden="" class="ng-pristine ng-untouched ng-valid">
                                            <input type="text" name="cpdref" style="width:100%;" id="cpdref" ng-model="cpdRefSelect" class="ng-pristine ng-untouched ng-valid">
                                            <select name="CompoundRef[]" id="Cpdrefselect" multiple="" size="10" style="width:100%;" ng-model="cpdRefSelect"  class="ng-pristine ng-valid ng-touched">
                                                <option  value="1">
                                                    cont1
                                                </option>
                                                <option  value="2">
                                                    cont2
                                                </option>
                                                <option  value="3">
                                                    exp1
                                                </option>
                                                <option  value="4">
                                                    exp2
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <input type="text" name="cpdsam" style="width:100%;" id="cpdsam" ng-model="cpdSamSelect" class="ng-pristine ng-untouched ng-valid">
                                            <select name="CompoundSam[]" id="Cpdsamselect" multiple="" size="10" style="width:100%;" ng-model="cpdSamSelect"  class="ng-pristine ng-untouched ng-valid">
                                               <option  value="1">
                                                    cont1
                                                </option>
                                                <option  value="2">
                                                    cont2
                                                </option>
                                                <option  value="3">
                                                    exp1
                                                </option>
                                                <option  value="4">
                                                    exp2
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
                                            <p style="display: inline;margin-right:10px;">Paired</p> <input style="display: inline;" type="checkbox" id="CpdCompare" name="cpdCompare" ng-model="CpdCompare" ng-init="checked=true" checked="true" class="ng-pristine ng-untouched ng-valid">
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
                    <input type="text" id="geneFileDetails" value="" hidden="">
                    <input type="text" id="compoundFileDetails" value="" hidden="">
                   @include('analysis')

    </div>


    </div>

</div>
        <script type="text/javascript">
            $( document ).ready(function() {

                var geneRefSelect =$('#generef').val().split(",");
                var cpdRefSelect = $('#cpdref').val().split(",");
                var geneSamSelect =$('#genesam').val().split(",");
                var cpdSamSelect =$('#cpdsam').val().split(",");

                $( geneRefSelect ).each(function( index,value ) {
                    $("#Generefselect option[value=\"" + value + "\"]").attr('selected', 'selected');
                    $("#Genesamselect option[value=\"" + value + "\"]").attr('disabled', 'disabled');

                });

                $( cpdRefSelect ).each(function( index,value ) {
                    $("#Cpdrefselect option[value=\"" + value + "\"]").attr('selected', 'selected');
                     $("#Cpdsamselect option[value=\"" + value + "\"]").attr('disabled', 'disabled');

                });

                $( geneSamSelect ).each(function( index,value ) {
                    $("#Generefselect option[value=\"" + value + "\"]").attr('disabled', 'disabled');
                    $("#Genesamselect option[value=\"" + value + "\"]").attr('selected', 'selected');

                });

                $( cpdSamSelect ).each(function( index,value ) {
                    $("#Cpdrefselect option[value=\"" + value + "\"]").attr('disabled', 'disabled');
                    $("#Cpdsamselect option[value=\"" + value + "\"]").attr('selected', 'selected');

                });



                $('#Generefselect').change(function () {


                    console.log("in generefselect function");
                    var ref_selected_text = "";
                    var noOfColumns = $('#geneColumns').val() ;

                    for (var j = 1; j <= 6; j++) {
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

                    for (var j = 1; j <= 4; j++) {
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
                    var ref_selected_text = "";


                    for (var j = 1; j < 5; j++) {
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
                    for (var j = 1; j < 5; j++) {
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
                console.log( $('#compoundFileDetails').val() );
                console.log( $('#geneFileDetails').val() );
                var geneFileDetails = $('#geneFileDetails').val();
                //gene data rendering
                if(geneFileDetails != null)
                {
                    var geneDataArray = geneFileDetails.split(";");
                    if(geneDataArray[0] != null) {
                        if (geneDataArray[0].split(":")[1] != null) {
                            $('#edit').css("visibility", "visible");
                            selectOptionsList = geneDataArray[0].split(":")[1].split(",");
                            $('#Genesamselect').find('option').remove();
                            $('#Generefselect').find('option').remove();
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
                        if(geneDataArray[1].split(":")[1] != null)
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

                }


                //compound data rendering
                var compoundFileDetails = $('#compoundFileDetails').val();

                if(compoundFileDetails != null) {
                    var compoundDataArray = compoundFileDetails.split(";");

                    //select fileds
                    if(compoundDataArray[0] != null) {
                        if(compoundDataArray[0].split(":")[1] != null) {
                            $('#cpdedit').css("visibility", "visible");
                            selectOptionsList = compoundDataArray[0].split(":")[1].split(",");
                            $('#Cpdsamselect').find('option').remove();
                            $('#Cpdrefselect').find('option').remove();
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

            </script>
        <script type="text/javascript">

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



        </script>
        <script src="{{asset('/js/analysis.js')}}"></script>

@stop

