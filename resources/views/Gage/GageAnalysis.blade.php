@extends('app')
@section('content')

    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.js"></script>
    <script src="js/app.js"></script>
    <script src="js/gageAnalysis.js"></script>
    <script src="js/app.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
    <div class="col-sm-9">
        <div class="conetent-header ">
            {{-- @if(is_null(Auth::user()))
                 <h2> Welcome Guest </h2>
             @else
             <p> Welcome: <b>@if(Auth::user()->name=="") {!! Auth::user()->email !!} @else {!! Auth::user()->name!!}, @endif </b></p>
             @endif--}}
            <p><b>Gage Analysis</b></p>

        </div>
        {!! form::open(array('url' => 'gageAnalysis','method'=>'POST','files'=>true,'id' => 'gage_anal_form')) !!}
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
                        </div>
                        <div class="col-sm-7">
                            <select  name="geneSet" id="geneSet"    multiple="" size="10" style="width:100%;">
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
                            <select  name="geneIdType" id="geneIdType"   class="KEGG">
                                <option value="entrez">entrez</option>
                                <option value="kegg">kegg</option>
                                <option value="custom">Custom</option>
                            </select>
                            <input name="geneIdFile" type="file"  id="geneIdFile" hidden=""   >
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
                            <input class="ex8" list="specieslist" name="species" id="species" value="hsa-Homo sapiens"  autocomplete="on">
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
                            <input class="ex8"  name="reference"  id="reference"  placeholder="1,2,3"  >
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
                            <input class="ex8"  name="samples"  id="sample" value="" placeholder="4,5,6" >
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

                                <input class="ex8"   name="cutoff"  id="cutoff" value="0.1"  placeholder="0.1">
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
                                Minimum: <input class="ex8" style="width:60px;"  name="setSizeMin"  id="setSizeMin" value="5"  placeholder="5">
                                <label id="setSizeMinError" hidden>SetSizeMin Should be neumeric</label>

                            </div>
                            <div class="col-sm-6">
                                Maximum:     <input class="ex8" style="width:60px;"  name="setSizeMax"  id="setSizeMax" value="100" placeholder="100">
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
                                <option value="paired">paired</option>
                                <option value="unpaired">unpaired</option>
                                <option value="1on group">1on group</option>
                                <option value="as.group">as.group</option>
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

                            <input type="checkbox" id="sameDir" value="true" name="sameDir" checked>
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
                            <input type="checkbox" id="rankTest" value="true" name="rankTest" checked>

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
                            <input type="checkbox"  id="useFold" value="true" name="useFold" checked>

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
                                <option value="gs.tTest">t-test</option>
                                <option value="gs.zTest">z-test</option>
                                <option value="gs.KSTest">KS-test</option>
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

                            <input type="checkbox" id="usePathview" value="true" name="usePathview" checked>
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
    rules: {
        assayData: {
            required:true,
            extension: "txt|csv"
        },
        geneSet: "required",
        reference: {
            required: true,
            refSampleFieldValidate:true
        },
        samples:{
            required: true,
            refSampleFieldValidate:true
        },
        setSizeMin:{
            required: true,
            digits: true
        },
        setSizeMax:{
            required: true,
            digits: true
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
        geneSet: "* Atleast one GeneSet Field option should be selected",
        reference:{
            required: "* Reference columns should be specified",
            refSampleFieldValidate: "Input field should be Column numbers of input file separated by comma"
        },
        samples: {
            required: "* Sample columns should be specified",
            refSampleFieldValidate: "Input field should be Column numbers of input file separated by comma"
        },
        setSizeMin: {
            required: "* Set Size Min field is required",
            digits: "* Set Size Min field must be numeric"
        },
        setSizeMax: {
            required: "* Set Size Max field is required",
            digits: "* Set Size Max field must be numeric"
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
        return /^\d(\d?\,?\d+)*/.test(value);
    },"Input field should be Column numbers of input file separated by comma");
    jQuery.validator.addMethod('decimal',function(value, element){
    return /^\d(\d?\.?\d+)*/.test(value);
        },"Cut off value should be a decimal value");
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

                if (specieValue['species_id'] + "-" + specieValue['species_desc'] === value || (value.length == 3 && specieValue['species_id'] === value )) {
                    validSpeciesFlag = true;
                }
            });

        }
        return validSpeciesFlag;
    },"Entered Speceis is not a valid speceis");

</script>
            <style>
                .error
                {
                    color: #a94442;
                    background-color: #f2dede;
                    border-color: #ebccd1;
                    font-size: 15px;
                }
            </style>
    </div>
        <div class="steps" style="margin-left:-2%">
            <input type="submit" id="submit-button" class="btn btn-primary" style="font-size: 20px;width: 30%;margin-left: 15%;;margin-top: 10px;float:left;" value="Submit" onclick="return validation()"  />
            <input type="Reset" id="reset" class="btn btn-primary" style="font-size: 20px;width: 30%;margin-left:10%;margin-top: 10px;;float:left;" value="Reset" />
        </div>
    </div>

    </div>




@stop