@extends('GageApp')
@section('content')
    @include('GageNavigation')

    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.js"></script>
    <script src="js/app.js"></script>
    <script src="js/gageAnalysis.js"></script>
    <script src="js/app.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
    <link href="{{ asset('/css/bootstrap-switch.min.css') }}" rel="stylesheet">
    <script src="{{ asset('/js/bootstrap-switch.min.js') }}"></script>
    <div class="col-md-9">
        <div class="conetent-header ">
            <p><b>Discrete GAGE Analysis</b></p>
        </div>
        <div id="error-message"></div>
  </div>
    <?php
    //specifying default values for all the variables;
    $geneIdType = "entrez";
    $species = "hsa-Homosapiens-human";
    $reference = "1,3";
    $sample = "2,4";
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
    <input type="text" id="rememberTxt" hidden="" >

    <div class="col-md-7">

        <div id="content">
            <div id="wrapper"  ng-app="GageApp" ng-controller="ExampleAnalysisController1">
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
                    {!! form::open(array('url' => 'discreteGageAnalysis','method'=>'POST','files'=>true,'id' => 'gage_anal_form','name'=>'gage_anal_form')) !!}
                    <fieldset class="step inputOutput-step">
                        <div class="stepsdiv" id="SampleListData-div">
                            <div class="col-sm-12">

                                <div class="col-sm-5">
                                    <a href="gageTutorial#sampleList" onclick="window.open('gageTutorial#assay_data', 'newwindow', 'width=300, height=250').focus() ;return false;" title="Input data containing an expression matrix or matrix-like data structure, with genes as rows and samples as columns. Accepts only CSV and TXT as extension." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                        <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                    </a>
                                    <label for="assayData">Sample List:</label>
                                </div>

                                <div class="col-sm-7">


                                    <div class="input-group">
                                        <span style="color:red" ng-show="userForm.files.$dirty && userForm.files.$invalid"></span>
                                        <textarea class="form-control valid" rows="4" name="sampleList" aria-invalid="false"></textarea>
                                        <label for="sampleList">(Or)</label>
                                        <input type="file" name="sampleListInputFile">
					                </div>
                                </div>
                            </div>
                        </div>
                    <div class="stepsdiv" id="BackgroundData-div">
                            <div class="col-sm-12">

                                <div class="col-sm-5">
                                    <a href="gageTutorial#sampleList" onclick="window.open('gageTutorial#assay_data', 'newwindow', 'width=300, height=250').focus() ;return false;" title="Input data containing an expression matrix or matrix-like data structure, with genes as rows and samples as columns. Accepts only CSV and TXT as extension." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                        <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                    </a>
                                    <label for="assayData">Background List:</label>
                                </div>

                                <div class="col-sm-7">

                                    <textarea class="form-control valid" rows="4" name="backgroundList" aria-invalid="false"></textarea>
                                    <label for="sampleList">(Or)</label>
                                    <div class="input-group">
                                        <span style="color:red" ng-show="userForm.files.$dirty && userForm.files.$invalid"></span>
                                        <input type="file" name="backgroundListInputFile">
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="stepsdiv" id="species-div">
                            <div class="col-sm-12">
                                <div class="col-sm-5">
                                    <a href="gageTutorial#species" onclick="window.open('gageTutorial#species', 'newwindow', 'width=300, height=250').focus();return false;" title="Either the KEGG code, scientific name or the common name of the target species. Species may also be 'ko' for KEGG Orthology pathways. Auto suggestions are provided."  target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                        <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                    </a>
                                    {!!form::label('species','Species:') !!}
                                </div>
                                <div class="col-sm-7" id="spciesList">
                                    <input class="ex8" style="width:100%" list="specieslist" name="species" id="species" value={{$species}}  autocomplete="off">
                                </div>
                            </div>
                            <datalist id="specieslist">
                                <!--[if (lt IE 10)]><select disabled style="display:none"><![endif]-->
                                <?php
        $species = DB::table('Species')->get();
        foreach ($species as $species1) {
            echo "<option>" . $species1->species_id . "-" . $species1->species_desc . "-" . $species1->species_common_name . "</option>";
        }
        ?>
        <!--[if (lt IE 10)]></select><![endif]-->
                            </datalist>
                        </div>
                        <div class="stepsdiv" id="gset-div">
                            <div class="col-sm-12">
                                <div class="col-sm-5">
                                    <a href="gageTutorial#gene_set" onclick="window.open('gageTutorial#gene_set', 'newwindow', 'width=300, height=250').focus() ;return false;" title="Multiple select option for Gene set which can be taken from 3 categories kegg, Go and Custom gene set uploaded using a txt or csv file." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                        <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                    </a>
                                    <label for="GeneSet">Gene Set:</label>
                                    <div class="col-sm-12">
                                        <?php if ( basename(Request::url())== "gageExample2"){   ?>
                                        <a id='geneIdFileResult' href="/all/demo/example/c1_all_v3_0_symbols.gmt" target="_blank" >c1_all_v3_0_symbols.gmt</a>
                                        <?php }?>
                                        <input name="geneIdFile" type="file"  id="geneIdFile" hidden=""   style="margin-top: 20px;font-size: 14px;">
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    <select  name="geneSet[]" id="geneSet" class="geneSet"  multiple="" size="10" style="width:100%;">
                                        <optgroup label="KEGG">
                                            <option value="sigmet.idx" >Signaling & Metabolic</option>
                                            <option value="sig.idx">Signaling</option>
                                            <option value="met.idx">Metabolic</option>
                                            <option value="dise.idx">Disease</option>
                                            <option value="sigmet.idx,dise.idx">All</option>
                                        </optgroup>
                                        <optgroup label="GO">
                                            <option value="BP">Biological Process</option>
                                            <option value="CC">Cellular Component</option>
                                            <option value="MF">Molecular Function</option>
                                            <option value="BP,CC,MF">All</option>
                                        </optgroup>
                                        <option value="custom" style="background-color: whitesmoke;font-weight: bold;margin-left:-1px;width:101%;">Custom</option>
                                    </select>

                                </div>
                            </div>

                        </div>

                        <div class="stepsdiv" id="geneIdType-div">
                            <div class="col-sm-12">
                                <div class="col-sm-5">
                                    <a href="gageTutorial#gene_id_type" onclick="window.open('gageTutorial#gene_id_type', 'newwindow', 'width=300, height=250').focus() ;return false;" title="ID type used for the Gene Data. This can be selected from the drop down list. for GO Gene sets the list is restricted to the Gene ID paired with species." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                        <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                    </a>
                                    <label for="geneIdType">Gene ID Type:</label>
                                </div>
                                <div class="col-sm-7" id="geneid">
                                    <select   class="styled-select" name="geneIdType" id="geneIdType" >
                                        <?php if ( basename(Request::url())== "gageExample2"){   ?>
                                        <option value="custom"   @if (strcmp($geneIdType,'kegg') == 0 ) selected @endif >custom</option>
                                        <?php }else { ?>
                                        <option value="ACCNUM" @if (strcmp(strtoupper($geneIdType),'ACCNUM') == 0 ) selected @endif>ACCNUM</option>
                                        <option value="ENSEMBL" @if (strcmp(strtoupper($geneIdType),'ENSEMBL') == 0 ) selected @endif >ENSEMBL</option>
                                        <option value="ENSEMBLPROT" @if (strcmp(strtoupper($geneIdType),'ENSEMBLPROT') == 0 ) selected @endif >ENSEMBLPROT</option>
                                        <option value="ENSEMBLTRANS" @if (strcmp(strtoupper($geneIdType),'ENSEMBLTRANS') == 0 ) selected @endif >ENSEMBLTRANS</option>
                                        <option value="ENTREZ" @if (strcmp(strtoupper($geneIdType),'ENTREZ') == 0 ) selected @endif >ENTREZ</option>
                                        <option value="ENZYME" @if (strcmp(strtoupper($geneIdType),'ENZYME') == 0 ) selected @endif >ENZYME</option>
                                        <option value="GENENAME" @if (strcmp(strtoupper($geneIdType),'GENENAME') == 0 ) selected @endif >GENENAME</option>
                                        <option value="KEGG" @if (strcmp(strtoupper($geneIdType),'KEGG') == 0 ) selected @endif >KEGG</option>
                                        <option value="PROSITE" @if (strcmp(strtoupper($geneIdType),'PROSITE') == 0 ) selected @endif >PROSITE</option>
                                        <option value="REFSEQ" @if (strcmp(strtoupper($geneIdType),'REFSEQ') == 0 ) selected @endif >REFSEQ</option>
                                        <option value="SYMBOL" @if (strcmp(strtoupper($geneIdType),'SYMBOL') == 0 ) selected @endif >SYMBOL</option>
                                        <option value="UNIGENE" @if (strcmp(strtoupper($geneIdType),'UNIGENE') == 0 ) selected @endif >UNIGENE</option>
                                        <option value="UNIPROT" @if (strcmp(strtoupper($geneIdType),'UNIPROT') == 0 ) selected @endif >UNIPROT</option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                        </div>



                    </fieldset >

                    <fieldset class="step analysis-step">

                        <div class="stepsdiv" id="cutoff-div">
                            <div class="col-sm-12">
                                <div class="col-sm-5">
                                    <a href="gageTutorial#q_value_cutoff" onclick="window.open('gageTutorial#q_value_cutoff', 'newwindow', 'width=300, height=250').focus() ;return false;" title="numeric, q-value cutoff between 0 and 1 for signficant gene sets selection." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                        <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                    </a>
                                    <label for="cutoff">q-value Cutoff:</label>
                                </div>
                                <div class="col-sm-7">

                                    <input class="ex8"   name="cutoff"  id="cutoff" value={{$cutoff}}  placeholder="0.1">
                                </div>
                            </div>
                        </div>

                        <div class="stepsdiv" id="setSize-div">
                            <div class="col-sm-12">
                                <div class="col-sm-5">
                                    <a href="gageTutorial#set_size" onclick="window.open('gageTutorial#set_size', 'newwindow', 'width=300, height=250').focus() ;return false;" title="Gene set size (number of genes) range to be considered for enrichment test. Tests for too small or too big gene sets are not robust statistically or informative bio-logically. " target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                        <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                    </a>
                                    <label for="setSize" >Set Size,</label>
                                    <label for="setSize" >Minimum:</label>
                                    <h4 for="setSize" style="max-width: 100%;margin-top:20px;font-weight: bold;margin-left: 116px;">Maximum:</h4>
                                </div>
                                <div class="col-sm-7">

                                    <input class="ex8" style=""  name="setSizeMin"  id="setSizeMin" value={{$setSizeMin}}  placeholder="5">
                                    <input class="ex8" style="margin-top:20px;"  name="setSizeMax"  class="MaxGreaterThanMinCheck" data-min="setSizeMin"  id="setSizeMax" value={{$setSizeMax}} placeholder="100">

                                </div>
                            </div>
                        </div>


                        <div class="stepsdiv" id="UsePathview-div">
                            <div class="col-sm-12">
                                <div class="col-sm-5">
                                    <a href="gageTutorial#use_pathview" onclick="window.open('gageTutorial#use_pathview', 'newwindow', 'width=300, height=250').focus() ;return false;" title="To perform pathview generation or not" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                        <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                    </a>
                                    <label for="usePathview">Use Pathview:</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="checkbox" id="usePathview" value="true" style="width: 44px;" name="dopathview" @if ($dopathview) checked @endif >
                                </div>
                            </div>
                        </div>

                        <div class="stepsdiv" id="resultBasedOn-div" >
           			<div class="col-sm-12">
                                <div class="col-sm-5">
                                    <a href="gageTutorial#resultBasedOn" onclick="window.open('gageTutorial#data_type', 'newwindow', 'width=300, height=250').focus() ;return false;" title="Data type Gene,Compound while generating the pathviews." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                        <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                    </a>
                                    <label for="ResultsBasedOn">Results Based On:</label>
                                </div>
                                <div class="col-sm-7" >
                                    <select name="resultBasedOn" class="styled-select" id="dataType" class="compare"  >
                                        <option value="number">number (selected)</option>
                                        <option value="ratio">ratio (selected vs background)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
			<div class="stepsdiv" id="dataType-div" >
                                <div class="col-sm-12">
                                <div class="col-sm-5">
                                    <a href="gageTutorial#data_type" onclick="window.open('gageTutorial#data_type', 'newwindow', 'width=300, height=250').focus() ;return false;" title="Data type Gene,Compound while generating the pathviews." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                        <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                    </a>
                                    <label for="usePathview">Data Type:</label>
                                </div>
                                <div class="col-sm-7" >
                                    <select name="dataType" class="styled-select" id="dataType" class="compare"  >
                                        <option value="gene">gene</option>
                                        <option value="compound">compound</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </fieldset>
                </div>

                <div  class="steps" >
                    <input type="submit" id="submit-button" class="btn btn-primary" style="font-size: 20px;width: 30%;margin-left: 15%;;margin-top: 10px;float:left;" value="Submit" onclick="return validation()"  />
                    <input type="Reset" id="reset" class="btn btn-primary" style="font-size: 20px;width: 30%;margin-left:10%;margin-top: 10px;;float:left;" value="Reset" />
                </div>
            </div>
        </div>
    </div>
    <script>


        $('#submit-button').click(function(){
            //$('#progress').show();
            $('#completed').hide();
        });
        $('#geneIdFile').hide();
        $(document).on('change', '.btn-file :file', function() {
            var input = $(this),
                    numFiles = input.get(0).files ? input.get(0).files.length : 1,
                    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [numFiles, label]);
        });

        $(document).ready( function() {
            $('.btn-file :file').on('fileselect', function(event, numFiles, label) {

                var input = $(this).parents('.input-group').find(':text'),
                        log = numFiles > 1 ? numFiles + ' files selected' : label;

                if( input.length ) {
                    input.val(log);
                } else {
                    if( log ) alert(log);
                }

            });
        });
        var goSpecIdBind = {"Anopheles":"eg",
            "Arabidopsis":"tair",
            "Bovine":"eg",
            "Worm":"eg",
            "Canine":"eg",
            "Fly":"eg",
            "Zebrafish":"eg",
            "E coli strain K12":"eg",
            "E coli strain Sakai":"eg",
            "Chicken":"eg",
            "Human":"eg",
            "Mouse":"eg",
            "Rhesus":"eg",
            "Malaria":"orf",
            "Chimp":"eg",
            "Rat":"eg",
            "Yeast":"orf",
            "Pig":"eg",
            "Xenopus":"eg"
        };

        //saved species to be used in javascript
        var speciesArray = <?php echo JSON_encode($species);?> ;
                <?php
                $goSpecies = DB::table('Species')
                        ->join('GoSpecies', 'Species.species_id', '=', 'GoSpecies.species_id')
                        ->select('GoSpecies.species_id','Species.species_desc','GoSpecies.Go_name','GoSpecies.id_type')->get();
                $GageSpeciesGeneIDMAtch = DB::table('GageSpeceisGeneIdMatch')
                                            ->select('species_id','geneid')->get();
                ?>
                var goSpeciesArray = <?php echo JSON_encode($goSpecies);?>;
        var GageSpeciesGeneIDMAtch = <?php echo JSON_encode($GageSpeciesGeneIDMAtch);?>;
                <?php
                $species_disesae = DB::table('Species')->where('disease_index_exist','N')->get();?>
                var speciesdiseaseArray = <?php echo JSON_encode($species_disesae);?> ;
        $('#gage_anal_form').validate({

            invalidHandler: function(form, validator) {
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
                sampleListInputFile: {
                    extension: "txt|csv"
                },
                backgroundListInputFile: {
                    extension: "txt|csv"
                },
                'geneSet[]': "required",
                reference: {
                    refSampleFieldValidate:true,
                    refSampleSameColumnCheck:true,
                    required: {
                        depends: function(element){
                            return $("#sample").val()!=""
                        }
                    }
                },
                samples:{
                    refSampleFieldValidate:true,
                    refSampleSameColumnCheck:true,
                    required: {
                        depends: function(element){
                            return $("#reference").val()!=""
                        }
                    }
                },
                setSizeMin:{
                    required: true,
                    digits: true
                },
                setSizeMax:{
                    setSize: true,
                    MaxGreaterThanMinCheck:true,
                    setSizeMaxDigitCheck:true
                },
                cutoff:{
                    required: true,
                    decimal:true
                },
                geneIdFile:{
                    required:true
                },
                species: {
                    required:true,
                    speciesValid:true
                },
                compare: {
                    refSapleColumnLengthCheck:true
                }
            },
            messages: {
                assayData: {
                    required: "* Select the Input file csv/txt file",
                    extension: "* only txt and csv extensions are allowed"
                },
                geneIdFile: {
                    required: "* Select the Gene ID file csv/txt file"
                },
                'geneSet[]': "* At least one GeneSet Field option should be selected",
                reference: {
                    required: "* Reference columns should be specified",
                    refSampleFieldValidate: "* Input field should be Column numbers of input file separated by comma or NULL",
                    refSampleSameColumnCheck: "* column numbers should not intersect with each other"
                },
                samples: {
                    required: "* Sample columns should be specified",
                    refSampleFieldValidate: "* Input field should be Column numbers of input file separated by comma or NULL",
                    refSampleSameColumnCheck: "* column numbers should not intersect with each other"
                },
                setSizeMin: {
                    required: "* Set Size Min field is required",
                    digits: "* Set Size Min field must be numeric"
                },
                setSizeMax: {
                    setSize: "* Set Size Max field must be numeric or INFINITE",
                    MaxGreaterThanMinCheck: "* SetSizeMax value should be greater than Min",
                    setSizeMaxDigitCheck: "* Max size should only contains digits or inf to indicate infinity "
                },
                cutoff: {
                    required: "* Cutoff field value is required",
                    decimal: "* Cutoff field must be decimal value"
                },
                species: {
                    required: "* Species field value is required",
                    speciesValid: "* Entered speceis is not a valid species"
                },
                compare:{
                    refSapleColumnLengthCheck: "* Since Reference and Sample columns lengths are not equal select value to be unpaired "
                }
            }/*,
             submitHandler: function(form) {
             var fd = new FormData(document.querySelector("form"));
             $.ajax({
             url:$('#gage_anal_form').attr('action'),
             type:$('#gage_anal_form').attr('method'),
             data: fd,
             success: function(data){
             $("#resultLink").attr("href", "/gageResult?analysis_id="+data);

             hideProgress();
             showDoneResultMessage();
             },

             contentType: false,
             processData: false

             });
             }
             */
        });
        function showDoneResultMessage() {
            console.log("in show done result message");
            $('#completed').show();
        }
        function hideProgress() {
            console.log("in show done progress hide");
            $('#progress').hide();

        }


        jQuery.validator.addMethod('refSampleFieldValidate',function(value, element){
            if(value.toLowerCase() === "null" || value.toLowerCase() === "")
            {
                return true;
            }
            else {
                return /^\d(\d?\,?\d+)*/.test(value);
            }
        },"Input field should be Column numbers of input file separated by comma");
        jQuery.validator.addMethod('decimal',function(value, element){
            return /^\d(\d?\.?\d+)*/.test(value);
        },"Cut off value should be a decimal value");
        jQuery.validator.addMethod('setSize',function(value, element){
            if(value.toLowerCase() === 'infinite' || $.isNumeric(value) )
            {
                return true;
            }
            else
            {
                return false;
            }
        },"setSize Value should be neumeric or INF");
        jQuery.validator.addMethod('speciesValid',function(value, element){
            var validSpeciesFlag = false;

            if($('#geneIdType > option').length == 1  ) {
                if ($('#geneIdType > option')[0].text === 'custom') {
                    if (value.split('-')[0].toLowerCase() === 'custom') {
                        return true;
                    }
                    else {
                        return false;
                    }
                }
            }
            if($('#geneSet').val() !== null) {
                if ($('#geneSet').val()[0] === 'BP' || $('#geneSet').val()[0] === 'CC' || $('#geneSet').val()[0] === 'MF' || $('#geneSet').val()[0] === 'BP,CC,MF') {
                    $.each(goSpecIdBind, function (key1, value1) {
                        if (key1.toLowerCase() === value.toLowerCase()) {
                            validSpeciesFlag = true;
                            return false;
                        }
                    });
                }
            }
            if (value.split('-')[0].toLowerCase() == "custom")
            {
                validSpeciesFlag = true;
            }
            else {
                $.each(speciesArray, function (speciesIter, specieValue) {

                    if (specieValue['species_id'] === value.split('-')[0] || (value.length == 3 && specieValue['species_id'] === value )) {
                        validSpeciesFlag = true;
                        return false;
                    }
                });

            }

            $.each(goSpeciesArray, function (index, xyz) {
                if (goSpeciesArray[index]['species_id'].toLowerCase() === value.substr(0, 3).toLowerCase()) {
                    validSpeciesFlag = true;
                    return false;
                }
            });




            return validSpeciesFlag;

        },"Entered Speceis is not a valid speceis");

        //jquery validation method to check if number of columns specified in  reference is always
        //equal to sample columns

        jQuery.validator.addMethod("refSapleColumnLengthCheck",function(value,element)
        {
            $refArray = $('#reference').val().replace(/\,/g," ").trim().split(" ");
            $sampleArray = $('#sample').val().replace(/\,/g," ").trim().split(" ");

            if( $refArray.length != $sampleArray.length && ($('#compare').val() === 'paired') )
            {
                return false
            }else{
                return true;
            }
        },"Since Reference and Sample columns lengths are not equal select the argument 'compare' to be unpaired");
        jQuery.validator.addMethod("refSampleSameColumnCheck", function(value, element) {


            $refArray = $('#reference').val().replace(/\,/g," ").trim().split(" ");
            $sampleArray = $('#sample').val().replace(/\,/g," ").trim().split(" ");



            function containsAny(array1, array2) {
                for (var i = 0; i < array1.length; i++) {
                    for (var j = 0; j < array2.length; j++) {
                        if (array1[i] == array2[j]) return true;
                    }
                }
                return false;
            }

            if (containsAny($refArray, $sampleArray)  ) {
                if(($refArray[0] =="" && $sampleArray[0] ==""))
                {
                    return true;
                }
                else {
                    return false;
                }
            }
            else {
                return true;
            }

        },"Column numbers should not intersect with each other");

        jQuery.validator.addMethod('MaxGreaterThanMinCheck', function(value, element) {
            if(value.toLowerCase() === 'infinite')
            {
                return true;
            }
            else{
                return parseInt(value) > parseInt($('#setSizeMin').val());
            }

        }, "Max must be greater than min");
        jQuery.validator.addMethod('setSizeMaxDigitCheck', function(value, element) {
            if(value.toLowerCase() === 'infinite')
            {
                return true;
            }
            else{
                return $.isNumeric($("#setSizeMax").val());

            }

        }, "Max size should only contains digits or inf");

    </script>



@stop
