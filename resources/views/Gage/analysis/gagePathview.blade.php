@extends('GageApp')
@section('content')

    @include('GageNavigation')

    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.js"></script>
    <script src="js/app.js"></script>
    <script src="js/gageAnalysis.js"></script>
    <script src="js/app.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
    <script type="text/javascript" src="http://jscolor.com/example/jscolor/jscolor.js"></script>
    <link href="{{ asset('/css/bootstrap-switch.min.css') }}" rel="stylesheet">
    <script src="{{ asset('/js/bootstrap-switch.min.js') }}"></script>
    <div class="col-md-9">
        <div class="conetent-header ">
            <p><b>Example GAGE Analysis 1</b></p>
        </div>
        <div id="error-message"></div>
        {{--<div class="col-md-2">
            <div id="progress" class="col-md-12" hidden>
                <h2 class="alert alert-info"> Executing, Please wait. </h2>
                <img width="200px" hieght="200px" src="/images/load.gif">
            </div>
            <div id="completed" class="list-group col-md-12" hidden>
                <p> Completed Gage Analysis</p>
                <a id ='resultLink' href="/gageResult?analysis_id=" target="_blank">Click here to see results</a>
                <button id="backbutton" onclick="showWrapperForm()">Go Back</button>
            </div>
        </div>--}}
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

//pathview
    $kegg = true;
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
    $glmt = 1;
    $gbins = 10;
    $glow = "#00FF00";
    $gmid = "#D3D3D3";
    $ghigh = "#FF0000";
    $clmt = 1;
    $cbins = 10;
    $clow = "#0000FF";
    $cmid = "#D3D3D3";
    $chigh = "#FFFF00";
    $nsum = "sum";
    $ncolor = "transparent";

    ?>
    <input type="text" id="rememberTxt" hidden="" >

    <div class="col-md-7">

        <div id="content">
            <div id="wrapper"  ng-app="GageApp" ng-controller="ExampleAnalysisController1">
                <div id="navigation" style="display:none;">
                    <ul>
                        <li class="selected">
                            <a id="input" href="#">Input / Output</a>
                        </li>
                        <li id="analysis">
                            <a  id="analysisA" href="#">Analysis</a>
                        </li>

                        <li id="graphics">
                            <a id="graphicsA" href="#">Graphics</a>
                        </li>
                        <li id="coloration">
                            <a  href="#">Coloration</a>
                        </li>

                    </ul>
                </div>
                <div id="steps">
                    {!! form::open(array('url' => 'gagePathviewAnalysis','method'=>'POST','files'=>true,'id' => 'gage_anal_form','name'=>'gage_anal_form')) !!}
                    <fieldset class="step inputOutput-step">
                        <div class="stepsdiv" id="assayData-div">
                            <div class="col-sm-12">

                                <div class="col-sm-5">
                                    <a href="gageTutorial#assay_data" onclick="window.open('gageTutorial#assay_data', 'newwindow', 'width=300, height=250').focus() ;return false;" title="Input data containing an expression matrix or matrix-like data structure, with genes as rows and samples as columns. Accepts only CSV and TXT as extension." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                        <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                    </a>
                                    <label for="assayData">Assay Data:</label>
                                </div>

                                <div class="col-sm-7">


                                    <div class="input-group">
                                        <span style="color:red" ng-show="userForm.files.$dirty && userForm.files.$invalid"></span>
                                        <a href="/all/data/gagedata.txt" target="_blank">gse16873.csv</a>
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



                        <div class="stepsdiv" id="ref-div" >
                            <div class="col-sm-12">
                                <div class="col-sm-5">
                                    <a href="gageTutorial#contorl_reference" onclick="window.open('gageTutorial#contorl_reference', 'newwindow', 'width=300, height=250').focus() ;return false;" title="Column numbers for the reference condition or phenotype i.e. control group if you specify null than all the columns are considered as target experiments. " target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                        <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                    </a>
                                    <label for="ref">Control / Reference:</label>
                                </div>
                                <div class="col-sm-7">
                                    <input class="ex8"  name="reference"  id="reference"  value={{$reference}}> <h6 class="noteHint" >eg: 1,3,5 or NULL</h6>
                                    <!-- To get the number of column fields in a file and render it on ref and sample columns -->
                                    <input type="text"  name="NoOfColumns" value="<% columns.length %>" hidden="" id="NoOfColumns"   >
                                    <select name="ref[]" id="refselect"   multiple="" size="5" style="width:100%;" ng-model='refselect' ng-show="columns.length > 0">
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
                                    <a href="gageTutorial#case_sample" onclick="window.open('gageTutorial#case_sample', 'newwindow', 'width=300, height=250').focus() ;return false;" title="Column numbers for the target condition or phenotype i.e. experiment group in the exprs data matrix. if you specify null than all the columns other than ref are considered as target experiments." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                        <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                    </a>
                                    <label for="sample">Case / Sample:</label>
                                </div>
                                <div class="col-sm-7">
                                    <input class="ex8"  name="samples"  id="sample" value={{$sample}}  > <h6 class="noteHint">eg: 2,4,6 or NULL</h6>
                                    <select name="sample[]" id="sampleselect"  multiple="" size="5" style="width:100%;" ng-model='sampleselect' ng-show="columns.length > 0">
                                        <option ng-repeat="column in columns track by $index"
                                                value="<% $index+1 %>">
                                            <% column %>
                                        </option>
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

                        <div class="stepsdiv" id="compare-div">
                            <div class="col-sm-12">
                                <div class="col-sm-5">
                                    <a href="gageTutorial#compare" onclick="window.open('gageTutorial#compare', 'newwindow', 'width=300, height=250').focus() ;return false;" title="Comparison scheme to be used." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                        <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                    </a>
                                    <label for="compare">Compare:</label>
                                </div>
                                <div class="col-sm-7">
                                    <select  name="compare"  class="styled-select" id="compare" class="compare" >
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
                                    <a href="gageTutorial#two_direction_test" onclick="window.open('gageTutorial#two_direction_test', 'newwindow', 'width=300, height=250').focus() ;return false;" title="To test for changes changes towards both directions simultaneously." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                        <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                    </a>
                                    <label for="sameDir">Two-direction Test:</label>
                                </div>
                                <div class="col-sm-7">

                                    <input type="checkbox" id="sameDir" style="width: 44px;" value="true" name="test2d" @if ($test2d) checked @endif  >
                                </div>
                            </div>
                        </div>

                        <div class="stepsdiv" id="rankTest-div">
                            <div class="col-sm-12">
                                <div class="col-sm-5">
                                    <a href="gageTutorial#rank_test" onclick="window.open('gageTutorial#rank_test', 'newwindow', 'width=300, height=250').focus() ;return false;" title="whether do the optional rank based two-sample t-test." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
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
                                    <a href="gageTutorial#per_gene_score" onclick="window.open('gageTutorial#per_gene_score', 'newwindow', 'width=300, height=250').focus() ;return false;" title="Whether to use fold changes or t-test statistics as per gene statistics." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                        <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                    </a>
                                    <label for="useFold">Per Gene Score:</label>
                                </div>
                                <div class="col-sm-7">
                                    <input type="checkbox"  id="useFold" value="true"  data-off-text="t-test" data-on-text="fold" name="useFold" @if ($useFold) checked @endif>
                                </div>
                            </div>
                        </div>

                        <div class="stepsdiv" id="test-div">
                            <div class="col-sm-12">
                                <div class="col-sm-5">
                                    <a href="gageTutorial#gene_set_test" onclick="window.open('gageTutorial#gene_set_test', 'newwindow', 'width=300, height=250').focus() ;return false;" title="Function used for gene set tests for single array based analysis." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                        <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                    </a>
                                    <label for="test">Gene Set Test:</label>
                                </div>
                                <div class="col-sm-7">
                                    <select name="test" id="test" class="styled-select" class="compare">
                                        <option value="gs.tTest" @if (strcmp($test,'gs.tTest') == 0 ) selected @endif >t-test</option>
                                        <option value="gs.zTest" @if (strcmp($test,'gs.zTest') == 0 ) selected @endif >z-test</option>
                                        <option value="gs.KSTest" @if (strcmp($test,'gs.KSTest') == 0 ) selected @endif  >Kolmogorov–Smirnov test</option>
                                    </select>
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
                        <div class="stepsdiv" id="pathviewSettings-div" >
                            <div class="col-sm-12">
                                <div class="col-sm-5">
                                    <a href="gageTutorial#data_type" onclick="window.open('gageTutorial#data_type', 'newwindow', 'width=300, height=250').focus() ;return false;" title="Data type Gene,Compound while generating the pathviews." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                        <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                    </a>
                                    <label for="usePathview">Pathview Settings:</label>
                                </div>
                                <div class="col-sm-7" >
                                    <select name="pathviewSettings" class="styled-select" id="pathviewSettings" class="compare"  >
                                        <option value="default">Default</option>
                                        <option value="customize">Customize</option>
                                    </select>
                                </div>
                            </div>

                        </div>


                    </fieldset>


                <fieldset class="step">
                    <div class="stepsdiv" id="kegg-layer-div">
                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                <a href="tutorial#kegg" onclick="window.open('tutorial#kegg', 'newwindow', 'width=300, height=250').focus();return false;"
                                   title="Whether to render the pathway as native KEGG graph (.png) or using Graphviz layout engine (.pdf). " target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                </a>
                                {!!form::label('kegg','Kegg Native:') !!}
                                <input name="kegg" value="T" id="kegg" <?php if (isset(Session::get('Sess')['kegg'])) {echo "checked";} else {if ((Session::get('Sess') == NULL)) { if($kegg) {echo "checked";}}}?> type="checkbox" style="margin-left: 10px;">
                            </div>
                            <div class="col-sm-6">
                                <a href="tutorial#layer" onclick="window.open('tutorial#layer', 'newwindow', 'width=300, height=250').focus();return false;" title="Controls plotting layers: 1) if node colors be plotted in the same layer as the pathway graph when Kegg Native is checked, 2) if edge/node type legend be plotted in the same page when Kegg Native is unchecked. " target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                </a>
                                {!!form::label('layer','Same Layer:' ) !!}
                                <input name="layer" value="T" id="layer"
                                       <?php if (isset(Session::get('Sess')['layer'])) {echo "checked";} else {if ((Session::get('Sess') == NULL)) { if($layer) {echo "checked";}}}?> type="checkbox" style="margin-left: 10px;">
                            </div>
                        </div>
                    </div>

                    <div class="stepsdiv" id="desc-div">
                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                <a href="tutorial#desc" onclick="window.open('tutorial#desc', 'newwindow', 'width=300, height=250').focus();return false;" title="Whether gene data should be treated as discrete." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                </a>
                                {!!form::label('gdesc',' Descrete Gene:') !!}
                                <input name="gdisc" value="T" id="gdisc"
                                       <?php if (isset(Session::get('Sess')['gdisc'])) {echo "checked";} else {if ((Session::get('Sess') == NULL)) { if($gdisc) {echo "checked";}}}?> type="checkbox" style="margin-left: 10px;">
                            </div>
                            <div class="col-sm-6">
                                <a href="tutorial#desc" onclick="window.open('tutorial#desc', 'newwindow', 'width=300, height=250').focus();return false;" title="Whether compound data should be treated as discrete." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                </a>
                                {!!form::label('cdesc','Descrete Compound:') !!}
                                <input name="cdisc" value="T" id="cdisc"
                                       <?php if (isset(Session::get('Sess')['cdisc'])) {echo "checked";} else {if ((Session::get('Sess') == NULL)) { if($cdisc) {echo "checked";}}}?> type="checkbox" style="margin-left: 10px;">
                            </div>
                        </div>
                    </div>

                    <div class="stepsdiv" id="split-expand-div">
                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                <a href="tutorial#split" onclick="window.open('tutorial#split', 'newwindow', 'width=300, height=250').focus();return false;" title="Whether split node groups are split to individual nodes." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"> </span>
                                </a>
                                {!!form::label('split','Split Group:') !!}
                                <input name="split" value="T" id="split"<?php if (isset(Session::get('Sess')['split'])) {echo "checked";} else {if (Session::get('Sess') == NULL) {if($split) {echo "checked";}}}?> type="checkbox" style="margin-left: 10px;">
                            </div>
                            <div class="col-sm-6">
                                <a href="tutorial#expand" onclick="window.open('tutorial#expand', 'newwindow', 'width=300, height=250').focus();return false;" title="Whether the multiple-gene nodes are expanded into single-gene nodes" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                </a>
                                {!!form::label('expand','Expand Node:') !!}
                                <input name="expand" value="T" id="expand" <?php if (isset(Session::get('Sess')['expand'])) {echo "checked";} else {if (Session::get('Sess') == NULL) {if($expand) {echo "checked";}}}?> type="checkbox" style="margin-left: 10px;">
                            </div>
                        </div>
                    </div>

                    <div class="stepsdiv" id="multi-matchd-div">
                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                <a href="tutorial#multi" onclick="window.open('tutorial#multi', 'newwindow', 'width=300, height=250').focus();return false;" title="Whether multiple states (samples or columns) gene data or compound data should be integrated and plotted in the same graph." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                </a>
                                {!!form::label('multistate','Multi State:') !!}
                                <input name="multistate" value="T" id="multistate"<?php if (isset(Session::get('Sess')['multistate'])) {echo "checked";} else {if (Session::get('Sess') == NULL) {if($multistate) {echo "checked";}}}?> type="checkbox" style="margin-left: 10px;">
                            </div>
                            <div class="col-sm-6">
                                <a href="tutorial#match" onclick="window.open('tutorial#match', 'newwindow', 'width=300, height=250').focus();return false;" title="Whether the samples of gene data and Compound data are paired" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                </a>
                                {!!form::label('matchd','Match Data:') !!}
                                <input name="matchd" value="T" id="matchd"<?php if (isset(Session::get('Sess')['matchd'])) {echo "checked";} else {if (Session::get('Sess') == NULL) {if($matchd) {echo "checked";}}}?> type="checkbox" style="margin-left: 10px;">
                            </div>
                        </div>
                    </div>

                    <div class="stepsdiv" id="offset-div" <?php if (isset(Session::get('err_atr')['offset'])) {echo "style='background-color:#DA6666;'";} ?>>
                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                <a href="tutorial#clabel" onclick="window.open('tutorial#clabel', 'newwindow', 'width=300, height=250').focus();return false;" title="How much compound labels should be put above the default position or node center." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;"><span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span></a>
                                {!!form::label('offset','Compound Label Offset:') !!}
                            </div>
                            <div class="col-sm-6">
                                <input type="text" class="ex8" name="offset" id="offset" value="<?php echo isset(Session::get('Sess')['offset']) ? Session::get('Sess')['offset'] : $offset ?>">
                            </div>
                        </div>
                    </div>


                    <div class="stepsdiv" id="kalgin-div">
                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                <a href="tutorial#kalign" onclick="window.open('tutorial#kalign', 'newwindow', 'width=300, height=250').focus();return false;" title="How the color keys are aligned when both Gene Data and Compound Data are not NULL." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                </a>
                                {!!form::label('align','Key Alignment:') !!}
                            </div>
                            <div class="col-sm-6">
                                {!!form::select('align', array('x' => 'x', 'y' => 'y'), isset(Session::get('Sess')['align']) ? Session::get('Sess')['align'] : $align) !!}
                            </div>
                        </div>
                    </div>

                    <div class="stepsdiv" id="pos-kpos-div">
                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                <a href="tutorial#spos" onclick="window.open('tutorial#spos', 'newwindow', 'width=300, height=250').focus();return false;" title="Controls the position of pathview signature." target="_blank" class="scrollToTop" style="float:left;margin-right:5px">
                                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                </a>
                                <div class="col-sm-6" style="margin-left: -20px;">
                                    {!!form::label('pos','Signature
                                    Position:',array('class' => 'awesome')) !!}
                                </div>
                                <div class="col-sm-6" style="margin-left: -40px;">
                                    {!! form::select('pos', array('bottomleft' => 'bottom left', 'bottomright' => 'bottom
                                    right','topleft' => 'top left', 'topright' => 'top right','none' => 'none'),isset(Session::get('Sess')['pos']) ?
                                    Session::get('Sess')['pos'] : $pos) !!}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <a href="tutorial#kpos" onclick="window.open('tutorial#kpos', 'newwindow', 'width=300, height=250').focus();return false;" title="Controls the position of color key(s)." target="_blank" class="scrollToTop" style="float:left;margin-right:5px">
                                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                </a>
                                <div class="col-sm-6" style="margin-left: -30px;">

                                    <h4 style="
                    margin-top: 1px;
  max-width: 100%;
  font-weight: bold;">Key </h4><h4 style="
  max-width: 100%;
  font-weight: bold;">Position:</h4>
                                </div>
                                <div class="col-sm-6" style="margin-left: -40px;">
                                    {!! form::select('kpos', array('bottomleft' => 'bottom left', 'bottomright' => 'bottom
                                    right','topleft' => 'top left', 'topright' => 'top right','none' => 'none'),isset(Session::get('Sess')['kpos']) ?
                                    Session::get('Sess')['kpos'] : $kpos) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="step">
                    <div class="stepsdiv" id="nodesum-div" <?php if (isset(Session::get('err_atr')['nodesun'])) {echo "style='background-color:#DA6666;'";} ?>>
                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                <a href="tutorial#nsum" onclick="window.open('tutorial#nsum', 'newwindow', 'width=300, height=250').focus();return false;" title="The method name to calculate node summary given that multiple genes or compounds are mapped to it." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                </a>
                                {!!form::label('nodesum','Node Sum:',array('class' => 'awesome')) !!}
                            </div>

                            <div class="col-sm-6">
                                {!! form::select('nodesun', array('sum' => 'sum', 'mean' => 'mean','median' => 'median', 'max'=> 'max','max.abs' => 'max.abs' ,'random' => 'random'),isset(Session::get('Sess')['nodesun']) ?Session::get('Sess')['nodesun'] : $nsum) !!}
                            </div>
                        </div>
                    </div>

                    <div class="stepsdiv" id="nacolor-div" <?php if (isset(Session::get('err_atr')['nacolor'])) {echo "style='background-color:#DA6666;'";} ?> class="coloration">
                        <div class="col-sm-12">
                            <div class="col-sm-6">
                                <a href="tutorial#ncolor" onclick="window.open('tutorial#ncolor', 'newwindow', 'width=300, height=250').focus();return false;"  title="Color used for NA's or missing values in Gene Data and Compound Data." target="_blank" class="scrollToTop" style="float:left;">
                                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                </a>
                                {!!form::label('nacolor','NA Color:') !!}
                            </div>
                            <div class="col-sm-6">
                                {!! form::select('nacolor', array('transparent' => 'transparent', 'grey' => 'grey'),isset(Session::get('Sess')['nacolor']) ?Session::get('Sess')['nacolor'] : $ncolor) !!}
                            </div>

                        </div>
                    </div>
                    <div class="stepsdiv" style="background-color: rgba(32, 80, 129, 0.16);">
                        <div class="col-sm-4">
                            <span style="margin-left:50px;font-weight: bold;"><u>Attribute</u></span>
                        </div>
                        <div class="col-sm-8">
                            <span style="margin-left:20px;font-weight: bold;"><u>Gene</u></span>
                            <span style="margin-left:100px;font-weight: bold;"><u>Compound</u></span>
                        </div>

                    </div>
                    <div class="stepsdiv" id="glmt-clmt-div" <?php if (isset(Session::get('err_atr')['clmt'])) {echo "style='background-color:#DA6666;'";} ?><?php if (isset(Session::get('err_atr')['glmt'])) {echo "style='background-color:#DA6666;'";} ?>>
                        <div class="col-sm-12">
                            <div class="col-sm-4">
                                <a href="tutorial#limit" onclick="window.open('tutorial#limit', 'newwindow', 'width=300, height=250').focus();return false;" title="The limit values for Gene Data and Compound Data when converting them to pseudo colors.This field is a numeric field you can enter two values separated by a comma for example 1,2." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                </a>
                                {!!form::label('limit',' Limit:') !!}
                            </div>

                            <div class="col-sm-8">
                                {!!form::text('glmt',isset(Session::get('Sess')['glmt']) ? Session::get('Sess')['glmt']:$glmt,array('class' => 'coloration', 'id' => 'glmt')) !!}
                                {!!form::text('clmt',isset(Session::get('Sess')['clmt']) ? Session::get('Sess')['clmt'] :$clmt,array('class' => 'coloration', 'id' => 'clmt','style'=>'margin-left:50px')) !!}
                            </div>
                        </div>
                    </div>


                    <div class="stepsdiv" id="gbins-cbins-div"<?php if (isset(Session::get('err_atr')['gbins'])) {echo "style='background-color:#DA6666;'";} ?><?php if (isset(Session::get('err_atr')['cbins'])) {echo "style='background-color:#DA6666;'";} ?>>
                        <div class="col-sm-12">
                            <div class="col-sm-4">
                                <a href="tutorial#bins" onclick="window.open('tutorial#bins', 'newwindow', 'width=300, height=250').focus();return false;" title="This argument specifies the number of levels or bins for Gene Data and Compound Data when converting them to pseudo colors." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                </a>
                                {!!form::label('Bins',' Bins:')!!}
                            </div>
                            <div class="col-sm-8">
                                {!!form::text('gbins',isset(Session::get('Sess')['gbins']) ?Session::get('Sess')['gbins'] :$gbins,array('class' => 'coloration', 'id' => 'gbins')) !!}
                                {!!form::text('cbins',isset(Session::get('Sess')['cbins']) ? Session::get('Sess')['cbins'] :$cbins,array('class' => 'coloration', 'id' => 'cbins','style'=>'margin-left:50px')) !!}
                            </div>
                        </div>
                    </div>


                    <div class="stepsdiv" id="glow-clow-div"<?php if (isset(Session::get('err_atr')['glow'])) {echo "style='background-color:#DA6666;'";} ?><?php if (isset(Session::get('err_atr')['clow'])) {echo "style='background-color:#DA6666;'";} ?>>
                        <div class="col-sm-12">
                            <div class="col-sm-4">
                                <a href="tutorial#color" onclick="window.open('tutorial#color', 'newwindow', 'width=300, height=250').focus();return false;" title="These arguments specify specifies the color spectra to code Gene Data and Compound Data" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                </a>
                                {!!form::label('glow',' Low:')!!}
                            </div>


                            <div class="col-sm-8">
                                <input class="color coloration {hash:true}" id="glow" name="glow" value={{isset(Session::get('genecolor')['glow']) ? Session::get('genecolor')['glow'] : '#00FF00'}}>
                                <input class="color coloration {hash:true}" id="clow" name="clow" style="margin-left:50px;" value={{isset(Session::get('genecolor')['clow']) ? Session::get('genecolor')['clow'] : '#0000FF'}}>
                            </div>
                        </div>
                    </div>

                    <div class="stepsdiv" id="gmid-cmid-div" <?php if (isset(Session::get('err_atr')['gmid'])) {echo "style='background-color:#DA6666;'";} ?><?php if (isset(Session::get('err_atr')['cmid'])) {echo "style='background-color:#DA6666;'";} ?>>
                        <div class="col-sm-12">
                            <div class="col-sm-4">
                                <a href="tutorial#color" onclick="window.open('tutorial#color', 'newwindow', 'width=300, height=250').focus();return false;" title="These arguments specify specifies the color spectra to code Gene Data and Compound Data" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                                </a>
                                {!!form::label('gmid',' Mid:')!!}
                            </div>
                            <div class="col-sm-8">
                                <input class="color coloration {hash:true}" id="gmid" name="gmid" value={{isset(Session::get('genecolor')['gmid']) ? Session::get('genecolor')['gmid'] : '#D3D3D3'}}>
                                <input class="color coloration {hash:true}" id="cmid" name="cmid"  style="margin-left:50px" value={{isset(Session::get('genecolor')['cmid']) ? Session::get('genecolor')['cmid'] : '#D3D3D3'}} >
                            </div>
                        </div>
                    </div>

                    <div class="stepsdiv" id="ghigh-chigh-div"<?php if (isset(Session::get('err_atr')['ghigh'])) {echo "style='background-color:#DA6666;'";} ?><?php if (isset(Session::get('err_atr')['chigh'])) {echo "style='background-color:#DA6666;'";} ?>>
                        <div class="col-sm-12">
                            <div class="col-sm-4">
                                <a href="tutorial#color" onclick="window.open('tutorial#color', 'newwindow', 'width=300, height=250').focus();return false;"
                                   title="These arguments specify specifies the color spectra to code Gene Data and Compound Data"
                                   target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span></a>
                                {!!form::label('ghigh',' High:') !!}

                            </div>
                            <div class="col-sm-8">
                                <input class="color coloration {hash:true}" id="ghigh" name="ghigh"  value={{isset(Session::get('genecolor')['ghigh']) ? Session::get('genecolor')['ghigh'] : '#FF0000'}}>
                                <input class="color coloration {hash:true}" id="chigh" name="chigh"  style="margin-left:50px" value={{isset(Session::get('genecolor')['chigh']) ? Session::get('genecolor')['chigh'] : '#FFFF00'}}>
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
                assayData: {
                    required:true,
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
                },
                offset:{
                    required: true,
                    number: true
                },
                glmt:{
                    required: true,
                    number: true
                },
                clmt:{
                    required: true,
                    number: true
                },
                gbins:{
                    required: true,
                    number: true
                },
                bins:{
                    required: true,
                    number: true
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

                            $(window).bind('beforeunload', function() {


                                var geneIdSelected = $( "#geneIdType").val();
                                var geneSetSelected = $('#geneSet').val();
                                var referenceSelected = $('#refselect').val();
                                var sampleSelected = $('#sampleselect').val();
                                var dataTypeSelected = $('#dataType').val();
                                var usePathview = $('#usePathview').is(":checked");
                                var columns=[];
                                $("#refselect option").each(function()
                                {
                                    columns.push($(this).text()); // Add $(this).val() to your list
                                });
                                console.log('Columns:'+columns);
                                console.log('referenceSelected:'+referenceSelected);
                                console.log('sampleSelected:'+sampleSelected);
                                console.log('geneSetSelected:'+geneSetSelected);
                                console.log('dataTypeSelected:'+dataTypeSelected);
                                console.log('geneIdSelected:'+geneIdSelected);
                                console.log('usePathview:'+usePathview);

                                //adding the dynamically added text hidden input variable

                                $val = 'col:';
                                if(columns != null || columns != undefined) {
                                    $.each(columns, function (index, value) {
                                        value = value.replace(/(\r\n|\n|\r|\s)/gm, "");
                                        $val += value;
                                        $val += ',';
                                    });
                                }
                                $val += ';';
                                $val += 'ref:';
                                if(referenceSelected != null || referenceSelected != undefined  ) {
                                    $.each(referenceSelected, function (index, value) {
                                        value = value.replace(/(\r\n|\n|\r|\s)/gm, "");
                                        $val += value;
                                        $val += ',';
                                    });
                                }
                                $val += ';';

                                $val += 'sam:';

                                if(sampleSelected != null || sampleSelected != undefined ) {
                                    $.each(sampleSelected, function (index, value) {
                                        value = value.replace(/(\r\n|\n|\r|\s)/gm, "");
                                        $val += value;
                                        $val += ',';
                                    });
                                }
                                $val += ';';

                                $val += 'gen:';
                                if(geneSetSelected != null || geneSetSelected != undefined ) {
                                    $.each(geneSetSelected, function (index, value) {
                                        value = value.replace(/(\r\n|\n|\r|\s)/gm, "");
                                        $val += value;
                                        $val += ',';
                                    });
                                }
                                $val += ';';

                                $val += 'gid:'+geneIdSelected+';';
                                if(usePathview)
                                    $val += 'pat:true;';
                                else
                                    $val += 'pat:false;';
                                $val += 'dat:'+dataTypeSelected+';';

                                console.log($val);
                                $('#rememberTxt').val($val);


                            });


                            $(function() {
                                var isOpera = !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
                                // Opera 8.0+ (UA detection to detect Blink/v8-powered Opera)
                                var isFirefox = typeof InstallTrigger !== 'undefined';   // Firefox 1.0+
                                var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
                                // At least Safari 3+: "[object HTMLElementConstructor]"
                                var isChrome = !!window.chrome && !isOpera;              // Chrome 1+
                                var isIE = /*@cc_on!@*/false || !!document.documentMode;   // At least IE6

                                if(isChrome||isIE||isSafari||isFirefox) {
                                    var each = $('#rememberTxt').val().split(';').slice(0);
                                    console.log(each);
                                    $.each(each, function (index, value) {
                                        if(value.substr(0,3)==='col')
                                        {
                                            columns =  value.substr(4);
                                        }
                                        else if(value.substr(0,3)==='ref'){
                                            reference = value.substr(4);
                                        }

                                        else if(value.substr(0,3)==='sam'){
                                            sample = value.substr(4);
                                        }

                                        else if(value.substr(0,3)==='gen'){
                                            geneSet = value.substr(4);
                                        }
                                        else if(value.substr(0,3)==='gid'){
                                            geneid = value.substr(4);
                                        }

                                        else if(value.substr(0,3)==='pat'){
                                            usePathview = value.substr(4);
                                        }

                                        else if(value.substr(0,3)==='dat'){
                                            dataTypeSelected = value.substr(4);
                                        }

                                    });

                                    if ($('#rememberTxt').val() === '' ) {

                                    }
                                    else {
                                        $('#sampleselect').attr('class', 'dynamicshow');
                                        $('#refselect').attr('class', 'dynamicshow');
                                        $('#sampleselect').show();
                                        $('#refselect').show();
                                        $colum = columns.split(',').slice(0);
                                        $colum.splice(($colum).length,1);
                                        $colum.splice(($colum).length-1,1);

                                        $.each($colum, function (index, value) {
                                            $('#sampleselect').append($("<option></option>")
                                                    .attr("value", index + 1).text(value));

                                            $('#refselect').append($("<option></option>")
                                                    .attr("value", index + 1).text(value));

                                        });
                                        $.each($colum,function (index,value) {
                                            $('#refselect option[value='+(index + 1)+']').attr('class','tempColumn');

                                            $('#sampleselect option[value='+(index + 1)+']').attr('class','tempColumn');

                                        });
                                        var refArray = reference.split(',').splice(0);
                                        refArray.splice((refArray).length,1);
                                        refArray.splice((refArray).length-1,1);
                                        $.each(refArray, function (index,value){
                                            $('#refselect option[value='+value+']').attr('selected','selected');
                                        });
                                        var sampleArray = sample.split(',').splice(0);
                                        sampleArray.splice((sampleArray).length,1);
                                        sampleArray.splice((sampleArray).length-1,1);
                                        $.each(sampleArray, function (index,value){
                                            $('#sampleselect option[value='+value+']').attr('selected','selected');
                                        });
                                        $('#NoOfColumns').val(($colum).length-1);
                                        if(geneid !== 'entrez' && geneid !== 'kegg' )
                                        {
                                            $('#geneIdType').empty();
                                            $('#geneIdType').append($("<option></option>").attr("value",geneid).text(geneid));
                                            if(geneid === 'custom')
                                            {
                                                $('#geneIdFile').show();
                                            }
                                        }
                                        if(usePathview=='true')
                                        {
                                            $('#dataType-div').show();
                                            $('#pathviewSettings-div').show();

                                        }
                                    }

                                }


                            });

                        </script>
<style>

    #gbins-error{
        display: block;


    }
    #cbins-error{

        display: block;

    }
    #glmt-error{
        display: block;
    }
    #clmt-error{
        display: block;
    }

</style>
@stop