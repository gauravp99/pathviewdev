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
            <p><b>Gage Analysis</b></p>

            <div id="error-message"></div>
        </div>
        {!! form::open(array('url' => 'gageAnalysis','method'=>'POST','files'=>true,'id' => 'gage_anal_form','name'=>'gage_anal_form')) !!}

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



            <fieldset class="step">
                <div class="stepsdiv" id="assayData-div">
                    <div class="col-sm-12">

                        <div class="col-sm-5">
                            <a>
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            <label for="assayData">Assay Data:</label>
                        </div>

                        <div class="col-sm-7">
                            <input name="assayData"    type="file"  id="assayData"  on-read-file="showContent($fileContent)" >
                            <span style="color:red" ng-show="userForm.files.$dirty && userForm.files.$invalid"></span>

                        </div>
                    </div>
                </div>
                <div class="stepsdiv" id="gset-div">
                    <div class="col-sm-12">
                        <div class="col-sm-5">
                            <a href="tutorial#gene_data" onclick="window.open('tutorial#gene_data', 'newwindow', 'width=300, height=250').focus() ;return false;" title="" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            <label for="GeneSet">Gene Set:</label>
                            <div class="col-sm-12">
                                <input name="geneIdFile" type="file"  id="geneIdFile" hidden=""   style="margin-top: 20px;font-size: 14px;">
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <select  name="geneSet[]" id="geneSet"    multiple="" size="10" style="width:100%;">
                                <optgroup label="KEGG">
                                    <option value="sigmet.idx">signalling</option>
                                    <option value="met.idx">metabolic</option>
                                    <option value="sig.idx">sigmet</option>
                                    <option value="dise.idx">disease</option>
                                    <option value="sigmet.idx,dise.idx">all</option>
                                </optgroup>
                                <optgroup label="GO">
                                    <option value="BP">bp</option>
                                    <option value="CC">cc</option>
                                    <option value="MF">mf</option>
                                    <option value="BP,CC,MF">all</option>
                                </optgroup>
                                <option value="custom">Custom</option>
                            </select>

                        </div>
                    </div>

                </div>
                <div class="stepsdiv" id="geneIdType-div">
                    <div class="col-sm-12">
                        <div class="col-sm-5">
                            <a href="tutorial#gene_data" onclick="window.open('tutorial#gene_data', 'newwindow', 'width=300, height=250').focus() ;return false;" title="" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            <label for="geneIdType">Gene ID Type:</label>
                        </div>
                        <div class="col-sm-7" id="geneid">
                            <select  name="geneIdType" id="geneIdType" class="KEGG">
                                <option value="entrez" @if (strcmp($geneIdType,'entrez') == 0 ) selected @endif >entrez</option>
                                <option value="kegg"   @if (strcmp($geneIdType,'kegg') == 0 ) selected @endif >kegg</option>
                            </select>

                        </div>
                    </div>

                </div>
                <div class="stepsdiv" id="species-div">
                    <div class="col-sm-12">
                        <div class="col-sm-5">
                            <a href="tutorial#species" onclick="window.open('tutorial#species', 'newwindow', 'width=300, height=250').focus();return false;" title="Either the KEGG code, scientific name or the common name of the target species. Species may also be 'ko' for KEGG Orthology pathways."  target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            {!!form::label('species','Species:') !!}
                        </div>
                        <div class="col-sm-7" id="spciesList">
                            <input class="ex8" list="specieslist" name="species" id="species" value={{$species}}  autocomplete="on">
                        </div>
                    </div>
                    <datalist id="specieslist">
                        <!--[if (lt IE 10)]><select disabled style="display:none"><![endif]-->
                        <?php
                        $species = DB::table('Species')->get();
                        foreach ($species as $species1) {
                            echo "<option>" . $species1->species_id . "-" . $species1->species_desc . "</option>";
                        }
                        ?>
                        <!--[if (lt IE 10)]></select><![endif]-->

                    </datalist>
                </div>
                <div class="stepsdiv" id="ref-div">
                    <div class="col-sm-12">
                        <div class="col-sm-5">
                            <a href="tutorial#gene_data" onclick="window.open('tutorial#gene_data', 'newwindow', 'width=300, height=250').focus() ;return false;" title="" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            <label for="ref">Reference / Control:</label>
                        </div>

                        <div class="col-sm-7">
                            <input class="ex8"  name="reference"  id="reference"  value={{$reference}}> <h6 class="noteHint" >eg: 1,3,5 or NULL</h6>
                            <!-- To get the number of column fields in a file -->
                            <input type="text"  name="NoOfColumns" value="<% columns.length %>" hidden="" id="NoOfColumns"   >
                                <select name="ref[]" id="refselect" multiple="" size="5" style="width:100%;" ng-show="columns.length > 0">
                                <option ng-repeat="column in columns track by $index"
                                        value="<% $index+1 %>">
                                    <% column %>
                                </option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="stepsdiv" id="sample-div">
                    <div class="col-sm-12">
                        <div class="col-sm-5">
                            <a href="tutorial#gene_data" onclick="window.open('tutorial#gene_data', 'newwindow', 'width=300, height=250').focus() ;return false;" title="" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            <label for="sample">Sample / Case:</label>
                        </div>

                        <div class="col-sm-7">
                            <input class="ex8"  name="samples"  id="sample" value={{$sample}}  > <h6 class="noteHint">eg: 2,4,6 or NULL</h6>
                            <select name="sample[]" id="sampleselect" multiple="" size="5" style="width:100%;" ng-show="columns.length > 0">
                                <option ng-repeat="column in columns track by $index"
                                        value="<% $index+1 %>">
                                    <% column %>
                                </option>

                            </select>
                        </div>
                    </div>
                </div>
    </fieldset >
            <fieldset class="step">
                <div class="stepsdiv" id="cutoff-div">
                    <div class="col-sm-12">
                        <div class="col-sm-5">
                            <a href="tutorial#gene_data" onclick="window.open('tutorial#gene_data', 'newwindow', 'width=300, height=250').focus() ;return false;" title="" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            <label for="cutoff">Cutoff:</label>
                        </div>
                        <div class="col-sm-7">

                                <input class="ex8"   name="cutoff"  id="cutoff" value={{$cutoff}}  placeholder="0.1">
                        </div>
                    </div>
                </div>
                <div class="stepsdiv" id="setSize-div">
                    <div class="col-sm-12">
                        <div class="col-sm-5">
                            <a href="tutorial#gene_data" onclick="window.open('tutorial#gene_data', 'newwindow', 'width=300, height=250').focus() ;return false;" title="" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            <label for="setSize">Set Size:</label>
                        </div>
                        <div class="col-sm-7">
                            <div class="col-sm-6">
                                Minimum: <input class="ex8" style="width:60px;"  name="setSizeMin"  id="setSizeMin" value={{$setSizeMin}}  placeholder="5">


                            </div>
                            <div class="col-sm-6">
                                Maximum:     <input class="ex8" style="width:60px;"  name="setSizeMax"  id="setSizeMax" value={{$setSizeMax}} placeholder="100">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="stepsdiv" id="compare-div">
                    <div class="col-sm-12">
                        <div class="col-sm-5">
                            <a href="tutorial#gene_data" onclick="window.open('tutorial#gene_data', 'newwindow', 'width=300, height=250').focus() ;return false;" title="" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            <label for="compare">Compare:</label>
                        </div>
                        <div class="col-sm-7">
                            <select  name="compare"  id="compare" class="compare">
                                <option value="paired" @if (strcmp($compare,'paired') == 0 ) selected @endif >paired</option>
                                <option value="unpaired" @if (strcmp($compare,'unpaired') == 0 ) selected @endif >unpaired</option>
                                <option value="1ongroup" @if (strcmp($compare,'1ongroup') == 0 ) selected @endif>1ongroup</option>
                                <option value="as.group" @if (strcmp($compare,'as.group') == 0 ) selected @endif >as.group</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="stepsdiv" id="sameDir-div">
                    <div class="col-sm-12">
                        <div class="col-sm-5">
                            <a href="tutorial#gene_data" onclick="window.open('tutorial#gene_data', 'newwindow', 'width=300, height=250').focus() ;return false;" title="" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            <label for="sameDir">Same Direction:</label>
                        </div>
                        <div class="col-sm-7">

                            <input type="checkbox" id="sameDir" style="width: 44px;" value="true" name="test2d" @if ($test2d) checked @endif  >
                        </div>
                    </div>

                </div>
                <div class="stepsdiv" id="rankTest-div">

                    <div class="col-sm-12">
                        <div class="col-sm-5">
                            <a href="tutorial#gene_data" onclick="window.open('tutorial#gene_data', 'newwindow', 'width=300, height=250').focus() ;return false;" title="" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            <label for="rankTest">Rank Test:</label>
                        </div>
                        <div class="col-sm-7">
                            <input type="checkbox" id="rankTest" style="width: 44px;" value="true" name="rankTest" @if ($rankTest) checked @endif  >

                        </div>
                    </div>
                </div>
                <div class="stepsdiv" id="useFold-div">

                    <div class="col-sm-12">
                        <div class="col-sm-5">
                            <a href="tutorial#gene_data" onclick="window.open('tutorial#gene_data', 'newwindow', 'width=300, height=250').focus() ;return false;" title="" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            <label for="useFold">Use Fold:</label>
                        </div>
                        <div class="col-sm-7">
                            <input type="checkbox"  id="useFold" value="true" style="width: 44px;" name="useFold" @if ($useFold) checked @endif>

                        </div>
                    </div>
                </div>
                <div class="stepsdiv" id="test-div">
                    <div class="col-sm-12">
                        <div class="col-sm-5">
                            <a href="tutorial#gene_data" onclick="window.open('tutorial#gene_data', 'newwindow', 'width=300, height=250').focus() ;return false;" title="" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            <label for="test">Test:</label>
                        </div>
                        <div class="col-sm-7">
                            <select name="test" id="test" class="compare">
                                <option value="gs.tTest" @if (strcmp($test,'gs.tTest') == 0 ) selected @endif >t-test</option>
                                <option value="gs.zTest" @if (strcmp($test,'gs.zTest') == 0 ) selected @endif >z-test</option>
                                <option value="gs.KSTest" @if (strcmp($test,'gs.KSTest') == 0 ) selected @endif  >KS-test</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="stepsdiv" id="UsePathview-div">
                    <div class="col-sm-12">
                        <div class="col-sm-5">
                            <a href="tutorial#gene_data" onclick="window.open('tutorial#gene_data', 'newwindow', 'width=300, height=250').focus() ;return false;" title="" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            <label for="usePathview">Use Pathview:</label>
                        </div>
                        <div class="col-sm-7">
                            <input type="checkbox" id="usePathview" value="true" style="width: 44px;" name="dopathview" @if ($dopathview) checked @endif >
                        </div>
                    </div>

                </div>
                <div class="stepsdiv" id="dataType-div" >
                    <div class="col-sm-12">
                        <div class="col-sm-5">
                            <a href="tutorial#gene_data" onclick="window.open('tutorial#gene_data', 'newwindow', 'width=300, height=250').focus() ;return false;" title="" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            <label for="usePathview">Data Type:</label>
                        </div>
                        <div class="col-sm-7" >
                            <select name="dataType" id="dataType" class="compare"  >
                                <option value="gene">gene</option>
                                <option value="compound">compound</option>
                            </select>
                        </div>
                    </div>

                </div>

            </fieldset>

<script>

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
$('#gage_anal_form').validate({
    invalidHandler: function(form, validator) {
        var errors = validator.numberOfInvalids();

        if (errors > 0) {
            $("#error-message").show().text("** You missed " + errors + " field(s) or errors check and fill it to proceed.");
        } else {
            console.log("no errors");
            $("#error-message").hide();
        }
    },
    rules: {
        assayData: {
            required:true,
            extension: "txt|csv"
        },
        'geneSet[]': "required",
        reference: {
            refSampleFieldValidate:true
        },
        samples:{
            refSampleFieldValidate:true
        },
        setSizeMin:{
            required: true,
            digits: true
        },
        setSizeMax:{
            setSize: true
        },
        cutoff:{
            required: true,
          decimal:true
        },
        geneIdFile:{
            required:true,
            extension: "txt|csv"
        },
        species: {
            required:true,
            speciesValid:true
        }
    },
    messages: {
        assayData: {
            required:"* Select the Input file csv/txt file",
            extension:"* only txt and csv extensions are allowed"
        },
        geneIdFile: {
            required:"* Select the Gene ID file csv/txt file",
            extension:"* only txt and csv extensions are allowed"
        },
        'geneSet[]': "* Atleast one GeneSet Field option should be selected",
        reference:{
            required: "* Reference columns should be specified",
            refSampleFieldValidate: "Input field should be Column numbers of input file separated by comma or NULL"
        },
        samples: {
            required: "* Sample columns should be specified",
            refSampleFieldValidate: "Input field should be Column numbers of input file separated by comma or NULL"
        },
        setSizeMin: {
            required: "* Set Size Min field is required",
            digits: "* Set Size Min field must be numeric"
        },
        setSizeMax: {
            setSize: "* Set Size Max field must be numeric or INF"
        },
        cutoff: {
            required: "* Cutoff field value is required",
            decimal: "* Cutoff field must be decimal value"
        },
        species: {
            required:"* Species field value is required",
            speciesValid:"* Entered speceis is not a valid species"
        }
    }
});

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
        if(value.toLowerCase() === 'inf' || $.isNumeric(value) )
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
            $.each(goSpecIdBind, function (key1, value1) {
                if( key1 === value )
                {
                    validSpeciesFlag = true;
                }
            });
        }
        else{
            $.each(speciesArray, function (speciesIter, specieValue) {

                if (specieValue['species_id']  === value.split('-')[0] || (value.length == 3 && specieValue['species_id'] === value )) {
                    validSpeciesFlag = true;
                }
            });

        }
        return validSpeciesFlag;
    },"Entered Speceis is not a valid speceis");

</script>

    </div>
        <div class="steps" style="margin-left:-2%">
            <input type="submit" id="submit-button" class="btn btn-primary" style="font-size: 20px;width: 30%;margin-left: 15%;;margin-top: 10px;float:left;" value="Submit" onclick="return validation()"  />
            <input type="Reset" id="reset" class="btn btn-primary" style="font-size: 20px;width: 30%;margin-left:10%;margin-top: 10px;;float:left;" value="Reset" />
        </div>
    </div>

    </div>




@stop