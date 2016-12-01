<?php

?>

@if (isset($example_analysis_4))
<?php  $autopathviewselection=true; ?>
@else
<?php  $autopathviewselection=false; ?>
@endif
            <script src="js/jquery.validate.min.js"></script>
            <link href="{{ asset('/css/bootstrap-switch.min.css') }}" rel="stylesheet">
            <script src="{{ asset('/js/bootstrap-switch.min.js') }}"></script>


            <script type="text/javascript">
                //this code is written to have the firefox browser refresh pressing the back button overriding the onload and onunload function
                window.onload = function() {  };
                window.onunload = function(){};
            </script>

           <!---
            Each div here is for each row in the form
            consist of following elements
            before label each div is having a info button with hover text and hyperlink to the help page
            1. Label : Label contains the string
            2. Element itself : Input element

            Error handling messages : We have considered two time error handling on the client side and on server side
            client side is done with jquery validation framework and server side is error handling is shown with checking the session attributes
            -->
            <div class="stepsdiv" id="species-div" @if (isset(Session::get('err_atr')['species'])) style='background-color:#DA6666;' @endif>
                <div class="col-sm-12">
                    <div class="col-sm-5">
                        <a href="tutorial#species"
                           onclick="window.open('tutorial#species', 'newwindow', 'width=500, height=500, status=1,scrollbars=1').focus();return false;"
                           title="Either the KEGG code, scientific name or the common name of the target species. Species may also be 'ko' for KEGG Orthology pathways."
                           target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                            <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                        </a>
                        {!!form::label('species','Species:') !!}
                    </div>
                    <div class="col-sm-7">
                        <input class="ex8" list="specieslist" name="species" id="species"
                               value=@if (isset(Session::get('Sess')['species']))  "{{Session::get('Sess')['species']}}" @else "{{$species}}" @endif
                        >
                    </div>
                </div>
                <datalist id="specieslist">
                    <!--[if (lt IE 10)]><select disabled style="display:none"><![endif]-->
                    <?php

                      $species = Cache::remember('Species', 10, function()
                    {
                        return DB::table('species')->get();
                    });
                    if (Cache::has('Species'))
                    {
                        $species = Cache::get('Species');
                    }

                    foreach ($species as $species1) {
                        //echo "<option>" . $species1->species_id . "-" . $species1->species_desc . "</option>";
                        echo "<option>" . $species1->species_id . "-" . $species1->species_desc . "-" . $species1->species_common_name . "</option>";
                    }
                    ?>
                    <!--[if (lt IE 10)]></select><![endif]-->
                </datalist>
            </div>

            <div class="stepsdiv" id="geneid-div"
                 @if (isset(Session::get('err_atr')['geneid'])) style="background-color:#DA6666;" @endif>

                <div class="col-sm-12">
                    <!--lable-->
                    <div class="col-sm-5">
                        <a href="tutorial#gene_id"
                           onclick="window.open('tutorial#gene_id', 'newwindow', 'width=500, height=500,scrollbars=1,status=1').focus();return false;"
                           title="ID type used for the Gene Data. This can be selected from the autosuggest drop down list."
                           target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                            <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                        </a>
                        {!!form::label('geneid','Gene ID Type:')!!}
                    </div>

                    <div class="col-sm-7">
                        <input class="ex8" list="geneidlist" name="geneid" id="geneid"
                               value=@if (isset(Session::get('Sess')['geneid'])) "{{Session::get('Sess')['geneid']}}" @else "{{$geneid}}" @endif
                               >
                    </div>
                </div>

                <!--Data list for autosuggestion for input element-->
                <datalist id="geneidlist">
                    <!--[if (lt IE 10)]><select disabled style="display:none"><![endif]-->
                    <?php
                    $gene = Cache::remember('gene', 10, function()
                    {
                        return DB::table('GageSpeceisGeneIdMatch')->where('species_id','hsa')->get();
                    });
                    if (Cache::has('gene'))
                    {
                        $gene = Cache::get('gene');
                    }

                    foreach ($gene as $gene1) {
                        echo "<option value='$gene1->geneid'>$gene1->geneid</option>";
                    }
                    ?>
                    <!--[if (lt IE 10)]></select><![endif]-->
                </datalist>
            </div>


            <div class="stepsdiv" id="cpdid-div" @if (isset(Session::get('err_atr')['cpdid'])) style='background-color:#DA6666;' @endif>
                <div class="col-sm-12">
                    <div class="col-sm-5">
                        <a href="tutorial#cpd_id"
                           onclick="window.open('tutorial#cpd_id', 'newwindow', 'width=500, height=500, status=1,scrollbars=1').focus();return false;"
                           title="ID type used for the Compound Data. This can be selected from the autosuggest drop down list."
                           target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                            <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                        </a>
                        {!!form::label('cpdid','Compound ID Type:') !!}
                    </div>
                    <div class="col-sm-7">
                        <input class="ex8" list="cpdidlist" name="cpdid" id="cpdid"
                               value=@if (isset(Session::get('Sess')['cpdid']))  "{{Session::get('Sess')['cpdid']}}" @else "{{$cpdid}}" @endif
                               >
                    </div>
                </div>

                <datalist id="cpdidlist">
                    <!--[if (lt IE 10)]><select disabled style="display:none"><![endif]-->
                    <?php
                     $compound = Cache::remember('compound', 10, function()
                    {
                        return DB::table('compoundID')->get();
                    });
                    if (Cache::has('compound'))
                    {
                        $compound = Cache::get('compound');

                    }

                    foreach ($compound as $compound1) { echo "<option>$compound1->compound_id</option>"; }
                      ?>
                    <!--[if (lt IE 10)]></select><![endif]-->
                </datalist>
            </div>



    <div class="stepsdiv" id="UsePathview-div">
        <div class="col-sm-12">
            <div class="col-sm-5">
                <a href="tutorial#cpd_id"
                   onclick="window.open('tutorial#cpd_id', 'newwindow', 'width=500, height=500, status=1,scrollbars=1').focus() ;return false;"
                   title="To perform pathview generation or not" target="_blank" class="scrollToTop"
                   style="float:left;margin-right:5px;">
                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                </a>

                    <label for="pathviewSelection">Pathway Selection:</label>

                </div>
                <div class="col-sm-7">
                    <input type="checkbox" id="pathviewSelection" value="false" data-size="small" style="width: 44px;" data-off-text="Manual" data-on-text="Auto" name="autopathviewselection"
                           @if ($autopathviewselection) checked @endif >
                </div>
	      </div>
</div>


            <div class="stepsdiv" @if (isset(Session::get('err_atr')['pathway'])) style='background-color:#DA6666;' @endif id="pat-select">
                <div class="col-sm-12">
                    <div class="col-sm-12">
                        <div class="col-sm-5">
                            <a href="tutorial#pwy_id"
                               onclick="window.open('tutorial#pwy_id', 'newwindow', 'width=500, height=500, status=1,scrollbars=1').focus();return false;"
                               title="KEGG pathway ID(s), usually 5 digit. Can be entered in 2 ways from select box and autosuggest text box."
                               target="_blank" class="scrollToTop" style="float:left;margin-right:5px;margin-left: -15px">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            {!!form::label('pathway','Pathway ID:') !!}

                        </div>
                    </div>



                    <div class="col-sm-5">

                        <input class="ex8" style="width:100%;font-size: 15px;" list="pathwaylist"
                               placeholder="Type in here or select below" name="pathway" id="pathway1"
                               value=@if (isset(Session::get('Sess')['pathway'])) {{Session::get('Sess')['pathway']}} @else '' @endif
                               autocomplete="on">
                        <br/>
                        <select name="selectfrom" style="float:none;width:100%;height:236px;font-size: 17px;" id="select-from"
                                multiple="" size="10" class="multiple-select">
                            <?php
                            $pathway = Cache::remember('Pathway', 10, function () {
                                return DB::table('pathway')->get();
                            });
                            if (Cache::has('Pathway')) {
                                $pathway = Cache::get('Pathway');
                            }


                            foreach ($pathway as $pathway1) {
                                echo "<option value=\"" . $pathway1->pathway_id . "-" . $pathway1->pathway_desc ."\">" . $pathway1->pathway_id . "-" . $pathway1->pathway_desc . "</option>";
                            }
                            ?>

                        </select>

                    </div>

                    <div class="col-sm-2" style="margin-left: 20px;">
                        <a class="margin-top:-10px" href="JavaScript:void(0);" id="btn-add1" style="font-size:24px;">

                            <span id="plus"
                                  style="box-shadow: 0px 0px 3px #AAA;border: 1px solid #060606;padding: 6px 57px 6px 59px;margin: 0px -50px -17px;"
                                  class="glyphicon glyphicon-plus"></span>
                        </a>

                        <div class="pathwayiconsep" style="margin-top: 60px">
                        </div>


                        <a class="margin-top:-10px" href="JavaScript:void(0);" id="btn-add" style="font-size:24px;">

                            <span id="forward" class="glyphicon glyphicon-forward"
                                  style="box-shadow: 0px 0px 3px #AAA;border: 1px solid #060606;padding: 6px;padding-left: 59px;margin: -50px;padding-right: 57px;margin-top: 30px;"></span>
                        </a>

                    </div>

                    <div class="col-sm-5" style="margin-left: -20px;">
                        <h6 style="font-family: Verdana;font-size=5px;color:black;margin-top: -24px;margin-left:10px;">Note: Remove
                            items by deleting</h6>
                        <textarea id="pathwayList" name="pathwayList" wrap="off"
                                  style="resize: none;float:none;width:100%;height:280px;font-size:16px;margin-left: 5px;" rows="11"
                                  cols="14">@if (isset(Session::get('Sess')['pathwayList'])) {{Session::get('Sess')['pathwayList']}} @else {{$selectpath}} @endif
                        </textarea>
                        <!--<textarea id="pathwayList" name="pathwayList" wrap="off"
                                  style="resize: none;float:none;width:100%;height:280px;font-size:16px;margin-left: 5px;" rows="11"
                                  cols="14">
				   @if ($autopathviewselection)
                                       '00000,' 
                                   @else
                                       @if (isset(Session::get('Sess')['pathwayList'])) 
 					   {{Session::get('Sess')['pathwayList']}}
				       @else
					   {{$selectpath}}
                                       @endif
                                   @endif
			</textarea>
                        //-->
                    </div>
                </div>
                <datalist id="pathwaylist">
                    <!--[if (lt IE 10)]><select disabled style="display:none"><![endif]-->
                    <?php
                    if (Cache::has('Pathway'))
                            {
                                $pathway = Cache::get('Pathway');
                            }
                    foreach ($pathway as $pathway1) {
                        echo "<option value=\"".$pathway1->pathway_id."-" . $pathway1->pathway_desc ."\">" . $pathway1->pathway_id . "-" . $pathway1->pathway_desc . "</option>";
                    }
                    ?>
                    <!--[if (lt IE 10)]></select><![endif]-->
                </datalist>
            </div>


            <div id="suffix-div" class="stepsdiv" @if (isset(Session::get('err_atr')['suffix'])) style='background-color:#DA6666;' @endif
             @if ($errors->has('suffix')) style="background-color:#DA6666;" @endif>
                <div class="col-sm-12">
                    <div class="col-sm-5">
                        <a href="tutorial#suffix"
                           onclick="window.open('tutorial#suffix', 'newwindow', 'width=500, height=500, status=1,scrollbars=1').focus();return false;"
                           title="The suffix to be added after the pathway name as part of the output graph file name."
                           target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                            <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                        </a>
                        {!!form::label('suffix','Output Suffix:') !!}
                    </div>
                    <div class="col-sm-7">
                        <input type="text" class="ex8" name="suffix" id="suffix"
                               value=@if (isset(Session::get('Sess')['suffix'])) {{Session::get('Sess')['suffix']}}  @else {{$suffix}} @endif >
                    </div>
                </div>
            </div>

            </fieldset>
            <fieldset class="step">
                <div class="stepsdiv" id="kegg-layer-div">
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <a href="tutorial#kegg"
                               onclick="window.open('tutorial#kegg', 'newwindow', 'width=500, height=500, status=1,scrollbars=1').focus();return false;"
                               title="Whether to render the pathway as native KEGG graph (.png) or using Graphviz layout engine (.pdf). "
                               target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            {!!form::label('kegg','Kegg Native:') !!}
                            <input name="kegg" value="T" id="kegg" @if (isset(Session::get('Sess')['kegg'])) checked @else
                                @if ((Session::get('Sess') == NULL))
                                    @if ($kegg)
                                        checked
                                    @endif
                                @endif
                            @endif type="checkbox" style="margin-left: 10px;">
                        </div>
                        <div class="col-sm-6">
                            <a href="tutorial#layer"
                               onclick="window.open('tutorial#layer', 'newwindow', 'width=500, height=500, status=1,scrollbars=1').focus();return false;"
                               title="Controls plotting layers: 1) if node colors be plotted in the same layer as the pathway graph when Kegg Native is checked, 2) if edge/node type legend be plotted in the same page when Kegg Native is unchecked. "
                               target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            {!!form::label('layer','Same Layer:' ) !!}
                            <input name="layer" value="T" id="layer"
                                   @if (isset(Session::get('Sess')['layer']))
                                       checked
                                   @else
                                       @if ((Session::get('Sess') == NULL))
                                           @if ($layer)
                                               checked
                                           @endif
                                       @endif
                                   @endif type="checkbox" style="margin-left: 10px;">
                        </div>
                    </div>
                </div>

                <div class="stepsdiv" id="desc-div">
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <a href="tutorial#desc"
                               onclick="window.open('tutorial#desc', 'newwindow', 'width=500, height=500, status=1,scrollbars=1').focus();return false;"
                               title="Whether gene data should be treated as discrete." target="_blank" class="scrollToTop"
                               style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            {!!form::label('gdesc',' Descrete Gene:') !!}
                            <input name="gdisc" value="T" id="gdisc"
                                   @if (isset(Session::get('Sess')['gdisc']))
                                       checked
                                   @else
                                       @if ((Session::get('Sess') == NULL))
                                           @if ($gdisc)
                                               checked
                                           @endif
                                       @endif
                                   @endif type="checkbox" style="margin-left: 10px;">
                        </div>
                        <div class="col-sm-6">
                            <a href="tutorial#desc"
                               onclick="window.open('tutorial#desc', 'newwindow', 'width=500, height=500, status=1,scrollbars=1').focus();return false;"
                               title="Whether compound data should be treated as discrete." target="_blank" class="scrollToTop"
                               style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            {!!form::label('cdesc','Descrete Compound:') !!}
                            <input name="cdisc" value="T" id="cdisc"
                                   @if (isset(Session::get('Sess')['cdisc']))
                                       checked
                                   @else
                                       @if ((Session::get('Sess') == NULL))
                                           @if ($cdisc)
                                                checked
                                           @endif
                                       @endif
                                   @endif type="checkbox" style="margin-left: 10px;">
                        </div>
                    </div>
                </div>

               <!-- <div class="stepsdiv" id="split-expand-div">
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <a href="tutorial#split"
                               onclick="window.open('tutorial#split', 'newwindow', 'width=500, height=500, status=1,scrollbars=1').focus();return false;"
                               title="Whether split node groups are split to individual nodes." target="_blank" class="scrollToTop"
                               style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"> </span>
                            </a>
                            {!!form::label('split','Split Group:') !!}
                            <input name="split" value="T" id="split"
                                   @if (isset(Session::get('Sess')['split']))
                                        checked
                                   @else
                                        @if (Session::get('Sess') == NULL)
                                            @if ($split)
                                                checked
                                            @endif
                                        @endif
                                   @endif type="checkbox" style="margin-left: 10px;" onclick="return false;" readonly="readonly">
                        </div>
                        <div class="col-sm-6">
                            <a href="tutorial#expand"
                               onclick="window.open('tutorial#expand', 'newwindow', 'width=500, height=500, status=1,scrollbars=1').focus();return false;"
                               title="Whether the multiple-gene nodes are expanded into single-gene nodes" target="_blank"
                               class="scrollToTop" style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            {!!form::label('expand','Expand Node:') !!}
                            <input name="expand" value="T" id="expand"
                                   @if (isset(Session::get('Sess')['expand']))
                                        checked
                                   @else
                                        @if (Session::get('Sess') == NULL)
                                            @if ($expand)
                                                checked
                                            @endif
                                        @endif
                                   @endif type="checkbox" style="margin-left: 10px;" onclick="return false;" readonly="readonly">
                        </div>
                    </div>
                </div>-->

                <div class="stepsdiv" id="multi-matchd-div">
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <a href="tutorial#multi"
                               onclick="window.open('tutorial#multi', 'newwindow', 'width=500, height=500, status=1,scrollbars=1').focus();return false;"
                               title="Whether multiple states (samples or columns) gene data or compound data should be integrated and plotted in the same graph."
                               target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            {!!form::label('multistate','Multi State:') !!}
                            <input name="multistate" value="T" id="multistate"
                                   @if (isset(Session::get('Sess')['multistate']))
                                       checked
                                   @else {
                                       @if (Session::get('Sess') == NULL)
                                           @if ($multistate)
                                               checked
                                           @endif
                                       @endif
                                   @endif type="checkbox" style="margin-left: 10px;">
                        </div>
                        <div class="col-sm-6">
                            <a href="tutorial#match"
                               onclick="window.open('tutorial#match', 'newwindow', 'width=500, height=500, status=1,scrollbars=1').focus();return false;"
                               title="Whether the samples of gene data and Compound data are paired" target="_blank"
                               class="scrollToTop" style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            {!!form::label('matchd','Match Data:') !!}
                            <input name="matchd" value="T" id="matchd   "
                                   @if (isset(Session::get('Sess')['matchd'])) {
                                        checked
                                   @else
                                        @if (Session::get('Sess') == NULL)
                                            @if ($matchd)
                                                checked
                                            @endif
                                        @endif
                                   @endif type="checkbox" style="margin-left: 10px;">
                        </div>
                    </div>
                </div>

                <div class="stepsdiv" id="offset-div" @if (isset(Session::get('err_atr')['offset'])) style='background-color:#DA6666;' @endif>
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <a href="tutorial#clabel"
                               onclick="window.open('tutorial#clabel', 'newwindow', 'width=500, height=500, status=1,scrollbars=1').focus();return false;"
                               title="How much compound labels should be put above the default position or node center."
                               target="_blank" class="scrollToTop" style="float:left;margin-right:5px;"><span
                                        class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span></a>
                            {!!form::label('offset','Compound Label Offset:') !!}
                        </div>
                        <div class="col-sm-6">
                            <input type="text" class="ex8" name="offset" id="offset"
                                   value=@if (isset(Session::get('Sess')['offset'])) {{Session::get('Sess')['offset']}} @else {{$offset}} @endif >
                        </div>
                    </div>
                </div>


                <div class="stepsdiv" id="kalgin-div">
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <a href="tutorial#kalign"
                               onclick="window.open('tutorial#kalign', 'newwindow', 'width=500, height=500, status=1,scrollbars=1').focus();return false;"
                               title="How the color keys are aligned when both Gene Data and Compound Data are not NULL."
                               target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
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
                            <a href="tutorial#spos"
                               onclick="window.open('tutorial#spos', 'newwindow', 'width=500, height=500, status=1,scrollbars=1').focus();return false;"
                               title="Controls the position of pathview signature." target="_blank" class="scrollToTop"
                               style="float:left;margin-right:5px">
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
                            <a href="tutorial#kpos"
                               onclick="window.open('tutorial#kpos', 'newwindow', 'width=500, height=500, status=1,scrollbars=1').focus();return false;"
                               title="Controls the position of color key(s)." target="_blank" class="scrollToTop"
                               style="float:left;margin-right:5px">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>

                            <div class="col-sm-6" style="margin-left: -30px;">

                                <h4 style="
                                margin-top: 1px;
                                max-width: 100%;
                                font-weight: bold;">Key </h4>
                                <h4 style="
                                max-width: 100%;
                                font-weight: bold;">Position:</h4>
                            </div>
                            <div class="col-sm-6" style="margin-left: -40px;">
                                {!! form::select('kpos', array('bottomleft' => 'bottom left', 'bottomright' => 'bottom
                                right','topleft' => 'top left', 'topright' => 'top right','none' => 'none'),isset(Session::get('Sess')['kpos']) ?
                                Session::get('Sess')['kpos'] : $kpos,array('id'=>'kpos'))!!}
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset class="step">
                <div class="stepsdiv" id="nodesum-div" @if (isset(Session::get('err_atr')['nodesun'])) style='background-color:#DA6666;' @endif>
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <a href="tutorial#nsum"
                               onclick="window.open('tutorial#nsum', 'newwindow', 'width=500, height=500, status=1,scrollbars=1').focus();return false;"
                               title="The method name to calculate node summary given that multiple genes or compounds are mapped to it."
                               target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            {!!form::label('nodesum','Node Sum:',array('class' => 'awesome')) !!}
                        </div>

                        <div class="col-sm-6">
                            {!! form::select('nodesun', array('sum' => 'sum', 'mean' => 'mean','median' => 'median', 'max'=> 'max','max.abs' => 'max.abs' ,'random' => 'random'),isset(Session::get('Sess')['nodesun']) ?Session::get('Sess')['nodesun'] : $nsum,array('id'=> 'nodesun')) !!}
                        </div>
                    </div>
                </div>

                <div class="stepsdiv" id="nacolor-div" @if (isset(Session::get('err_atr')['nacolor'])) style='background-color:#DA6666;' @endif
                 class="coloration">
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <a href="tutorial#ncolor"
                               onclick="window.open('tutorial#ncolor', 'newwindow', 'width=500, height=500, status=1,scrollbars=1').focus();return false;"
                               title="Color used for NA's or missing values in Gene Data and Compound Data." target="_blank"
                               class="scrollToTop" style="float:left;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            {!!form::label('nacolor','NA Color:') !!}
                        </div>
                        <div class="col-sm-6">
                            {!! form::select('nacolor', array('transparent' => 'transparent', 'grey' => 'grey'),isset(Session::get('Sess')['nacolor']) ?Session::get('Sess')['nacolor'] : $ncolor,array('id'=>'nacolor')) !!}
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
                <div class="stepsdiv" id="glmt-clmt-div" @if (isset(Session::get('err_atr')['clmt'])) style='background-color:#DA6666;' @endif
                <?php if (isset(Session::get('err_atr')['glmt'])) {
                    echo "style='background-color:#DA6666;'";
                } ?>>
                    <div class="col-sm-12">
                        <div class="col-sm-4">
                            <a href="tutorial#limit"
                               onclick="window.open('tutorial#limit', 'newwindow', 'width=500, height=500, status=1,scrollbars=1').focus();return false;"
                               title="The limit values for Gene Data and Compound Data when converting them to pseudo colors.This field is a numeric field you can enter two values separated by a comma for example 1,2."
                               target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            {!!form::label('limit',' Limit:') !!}
                        </div>

                        <div class="col-sm-4">
                            {!!form::text('glmt',isset(Session::get('Sess')['glmt']) ? Session::get('Sess')['glmt']:$glmt,array('class' => 'coloration', 'id' => 'glmt')) !!}
                        </div>
                        <div class="col-sm-4" style="align-content: left">
                            {!!form::text('clmt',isset(Session::get('Sess')['clmt']) ? Session::get('Sess')['clmt'] :$clmt,array('class' => 'coloration', 'id' => 'clmt','style'=>'margin-left:50px')) !!}
                        </div>
                    </div>
                </div>


                <div class="stepsdiv" id="gbins-cbins-div"<?php if (isset(Session::get('err_atr')['gbins'])) {
                    echo "style='background-color:#DA6666;'";
                } ?><?php if (isset(Session::get('err_atr')['cbins'])) {
                    echo "style='background-color:#DA6666;'";
                } ?>>
                    <div class="col-sm-12">
                        <div class="col-sm-4">
                            <a href="tutorial#bins"
                               onclick="window.open('tutorial#bins', 'newwindow', 'width=500, height=500, status=1,scrollbars=1').focus();return false;"
                               title="This argument specifies the number of levels or bins for Gene Data and Compound Data when converting them to pseudo colors."
                               target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            {!!form::label('Bins',' Bins:')!!}
                        </div>
                        <div class="col-sm-4">
                            {!!form::text('gbins',isset(Session::get('Sess')['gbins']) ?Session::get('Sess')['gbins'] :$gbins,array('class' => 'coloration', 'id' => 'gbins')) !!}
                        </div>
                        <div class="col-sm-4">
                            {!!form::text('cbins',isset(Session::get('Sess')['cbins']) ? Session::get('Sess')['cbins'] :$cbins,array('class' => 'coloration', 'id' => 'cbins','style'=>'margin-left:50px')) !!}
                        </div>
                    </div>
                </div>


                <div class="stepsdiv" id="glow-clow-div"<?php if (isset(Session::get('err_atr')['glow'])) {
                    echo "style='background-color:#DA6666;'";
                } ?><?php if (isset(Session::get('err_atr')['clow'])) {
                    echo "style='background-color:#DA6666;'";
                } ?>>
                    <div class="col-sm-12">
                        <div class="col-sm-4">
                            <a href="tutorial#color"
                               onclick="window.open('tutorial#color', 'newwindow', 'width=500, height=500, status=1,scrollbars=1').focus();return false;"
                               title="These arguments specify specifies the color spectra to code Gene Data and Compound Data"
                               target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            {!!form::label('glow',' Low:')!!}
                        </div>


                        <div class="col-sm-8">
                            <input class=" jscolor color coloration {hash:true}" id="glow" name="glow" autocomplete="on"
                                   value={{isset(Session::get('genecolor')['glow']) ? Session::get('genecolor')['glow'] : '#00FF00'}}>
                            <input class="jscolor color coloration {hash:true}" id="clow" name="clow" style="margin-left:50px;"
        autocomplete="on"                           value={{isset(Session::get('genecolor')['clow']) ? Session::get('genecolor')['clow'] : '#0000FF'}}>
                        </div>
                    </div>
                </div>

                <div class="stepsdiv" id="gmid-cmid-div" <?php if (isset(Session::get('err_atr')['gmid'])) {
                    echo "style='background-color:#DA6666;'";
                } ?><?php if (isset(Session::get('err_atr')['cmid'])) {
                    echo "style='background-color:#DA6666;'";
                } ?>>
                    <div class="col-sm-12">
                        <div class="col-sm-4">
                            <a href="tutorial#color"
                               onclick="window.open('tutorial#color', 'newwindow', 'width=500, height=500, status=1,scrollbars=1').focus();return false;"
                               title="These arguments specify specifies the color spectra to code Gene Data and Compound Data"
                               target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                            </a>
                            {!!form::label('gmid',' Mid:')!!}
                        </div>
                        <div class="col-sm-8">
                            <input class="jscolor color coloration {hash:true}" id="gmid" name="gmid" autocomplete="on"
                                   value={{isset(Session::get('genecolor')['gmid']) ? Session::get('genecolor')['gmid'] : '#D3D3D3'}}>
                            <input class="jscolor color coloration {hash:true}" id="cmid" name="cmid" style="margin-left:50px"
        autocomplete="on"                           value={{isset(Session::get('genecolor')['cmid']) ? Session::get('genecolor')['cmid'] : '#D3D3D3'}} >
                        </div>
                    </div>
                </div>

                <div class="stepsdiv" id="ghigh-chigh-div"<?php if (isset(Session::get('err_atr')['ghigh'])) {
                    echo "style='background-color:#DA6666;'";
                } ?><?php if (isset(Session::get('err_atr')['chigh'])) {
                    echo "style='background-color:#DA6666;'";
                } ?>>
                    <div class="col-sm-12">
                        <div class="col-sm-4">
                            <a href="tutorial#color"
                               onclick="window.open('tutorial#color', 'newwindow', 'width=500, height=500, status=1,scrollbars=1').focus();return false;"
                               title="These arguments specify specifies the color spectra to code Gene Data and Compound Data"
                               target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span></a>
                            {!!form::label('ghigh',' High:') !!}

                        </div>
                        <div class="col-sm-8">
                            <input class="jscolor color coloration {hash:true}" id="ghigh" name="ghigh" autocomplete="on"
                                   value={{isset(Session::get('genecolor')['ghigh']) ? Session::get('genecolor')['ghigh'] : '#FF0000'}}>
                            <input class="jscolor color coloration {hash:true}" id="chigh" name="chigh" style="margin-left:50px" autocomplete="on"
                                   value={{isset(Session::get('genecolor')['chigh']) ? Session::get('genecolor')['chigh'] : '#FFFF00'}}>
                        </div>
                    </div>
                </div>

            </fieldset>
            <input type="text" id="saveAttributes" name="saveAttributes" value="" hidden="">
            </div>
            <div class="steps">
                <!-- <input type="submit" id="submit-button" class="btn btn-primary" style="font-size: 20px;width: 30%;margin-left: 15%;;margin-top: 10px;float:left;" value="Submit" onclick="return fileCheck()"/> -->
		@if (isset($is_rest))
                   <input type="submit" id="submit-button" class="btn btn-primary"
                       style="font-size: 20px;width: 30%;margin-left: 15%;;margin-top: 10px;float:left;" value="Generate Query"
                       onclick="return attrSave()"/>
		@else
                   <input type="submit" id="submit-button" class="btn btn-primary"
                       style="font-size: 20px;width: 30%;margin-left: 15%;;margin-top: 10px;float:left;" value="Submit"
                       onclick="return attrSave()"/>
		@endif
			
                <input type="Reset" id="reset" class="btn btn-primary"
                       style="font-size: 20px;width: 30%;margin-left:10%;margin-top: 10px;;float:left;" value="Reset"
                       onclick="return reset()"/>
            </div>
            {!! form::close() !!}

            <script>


                $(document).ready(function () {
		//	$(window).unload( function () {

		  var selectpath= '<?php echo $selectpath ?>';
                  $("[name='autopathviewselection']").bootstrapSwitch();
		  $('#pathviewSelection').on('switchChange.bootstrapSwitch', function (event, state) {  
                  if($('#pathviewSelection').is(':checked')){
                       $('#pat-select').hide();
		       $('#pathwayList').val('00000');
                  }
		  else
		  {
		  $('#pat-select').show();
		  $('#pathwayList').val(selectpath);
		  }
		  }); 
                  if($('#pathviewSelection').is(':checked')){
                       $('#pat-select').hide();
		       $('#pathwayList').val('00000');
                  }
		  else
		  {
		  $('#pat-select').show();
		  }

                    //removing error message if exists
                    $("#reset").click(function(){
                        validator.resetForm();
                    });

                    //removing the extra comma if existed

                    //making the pathway list aligned using newline character
                    var pathway_string = "";
                    $('#pathwayList').val($.trim($('#pathwayList').val()));
                    var splittedPathwayArray = $('#pathwayList').val().split("\t");
                    if(splittedPathwayArray.length > 2)
                    {
                        console.log("reformating the pathway ids");
                        $.each($('#pathwayList').val().split("\t"),function(index,value){ pathway_string = pathway_string + $.trim(value) + "\t\r\n"; });
                        $('#pathwayList').val(pathway_string);
                    }



                  //function to load and reload the content of the page chrome reloads the page so need this functionality to be implemented

                    var savedAttributes = $('#saveAttributes').val();
                    if (savedAttributes !== '') {

                        var keyValueMap = savedAttributes.split(";");

                        if(keyValueMap.length>15)
                        {
                            var species = keyValueMap[0].split(":")[1];
                            var geneid = keyValueMap[1].split(":")[1];
                            var compoundid = keyValueMap[2].split(":")[1];
                            var glow = keyValueMap[3].split(":")[1];
                            var gmid = keyValueMap[4].split(":")[1];
                            var ghigh = keyValueMap[5].split(":")[1];
                            var clow = keyValueMap[6].split(":")[1];
                            var cmid = keyValueMap[7].split(":")[1];
                            var chigh = keyValueMap[8].split(":")[1];
                            var pathwayList1 = keyValueMap[9].split(":")[1];
                            //var pathwayList = pathwayList1.replace(new RegExp(",","g"),"\,\n");

                            var suffix = keyValueMap[10].split(":")[1];
                            var offset = keyValueMap[11].split(":")[1];
                            var glmt = keyValueMap[12].split(":")[1];
                            var clmt = keyValueMap[13].split(":")[1];
                            var gbins = keyValueMap[14].split(":")[1];
                            var cbins = keyValueMap[15].split(":")[1];
                            var kpos = 	keyValueMap[16].split(":")[1];
                            var pos = keyValueMap[17].split(":")[1];
                            var nodesun = keyValueMap[18].split(":")[1];
                            var nacolor = keyValueMap[19].split(":")[1];
                            var align = keyValueMap[20].split(":")[1];
                            var kegg = keyValueMap[21].split(":")[1];
                            var layer = keyValueMap[22].split(":")[1];
                            var gdisc = keyValueMap[23].split(":")[1];
                            var cdisc = keyValueMap[24].split(":")[1];
                            var split = keyValueMap[25].split(":")[1];
                            var expand = keyValueMap[26].split(":")[1];
                            var multistate = keyValueMap[27].split(":")[1];
                            var matchd = keyValueMap[28].split(":")[1];
                            var paired = keyValueMap[29].split(":")[1];
                            var geneColumns = keyValueMap[30].split(":")[1];
                            var cpdColumns = keyValueMap[31].split(":")[1];

                            $('#species').val(species);
                            $('#geneid').val(geneid);
                            $('#cpdid').val(compoundid);
                            $('#glow').val(glow);
                            $('#gmid').val(gmid);
                            $('#ghigh').val(ghigh);
                            $('#clow').val(clow);
                            $('#cmid').val(cmid);
                            $('#chigh').val(chigh);


                            $('#suffix').val(suffix);
                            $('#offset').val(offset);
                            $('#glmt').val(glmt);
                            $('#cglmt').val(clmt);
                            $('#gbins').val(gbins);
                            $('#cbins').val(cbins);
                            $('#kpos').val(kpos);
                            $('#pos').val(pos);
                            $('#nodesun').val(nodesun);
                            $('#nacolor').val(nacolor);
                            $('#align').val(align);

                            $('#geneColumns').val(geneColumns);
                            $('#NoOfColumns').val(cpdColumns);


                        if(kegg == "true")
                            $('#kegg').prop('checked', true);
                        else
                            $('#kegg').prop('checked', false);
                        if(layer == "true")
                            $('#layer').prop('checked', true);
                        else
                            $('#layer').prop('checked', false);
                        if(gdisc == "true")
                            $('#gdisc').prop('checked', true);
                        else
                            $('#gdisc').prop('checked', false);
                        if(cdisc == "true")
                            $('#cdisc').prop('checked', true);
                        else
                            $('#cdisc').prop('checked', false);
                          if(split == "true")
                            $('#split').prop('checked', true);
                          else
                              $('#split').prop('checked', false);

                        if(expand == "true")
                            $('#expand').prop('checked', true);
                        else
                            $('#expand').prop('checked', false);

                        if(multistate == "true")
                            $('#multistate').prop('checked', true);
                        else
                            $('#multistate').prop('checked', false);

                        if(matchd == "true")
                            $('#matchd').prop('checked', true);
                        else
                            $('#matchd').prop('checked', false);

                        if(paired == "true")
                            $('#GeneCompare').prop('checked', true);
                        else
                            $('#GeneCompare').prop('checked', false);



                        }

                        console.log(keyValueMap);
                        console.log(keyValueMap.length);


                    } else {
                        console.log("nothing in saveAttribute input element");
                    }


                });

                function attrSave() {

                    var species = $('#species').val();
                    var geneid = $('#geneid').val();
                    var compoundid = $('#cpdid').val();
                    var glow = $('#glow').val();
                    var gmid = $('#gmid').val();
                    var ghigh = $('#ghigh').val();
                    var clow = $('#clow').val();
                    var cmid = $('#cmid').val();
                    var chigh = $('#chigh').val();
                    var pathwayList = $('#pathwayList').val().replace(" ","");
                    var suffix = $('#suffix').val();
                    var offset = $('#offset').val();
                    var glmt = $('#glmt').val();
                    var clmt = $('#clmt').val();
                    var gbins = $('#gbins').val();
                    var cbins = $('#cbins').val();
                    var kpos = $('#kpos').val();
                    var pos =  $('#pos').val();
                    var nodesun =  $('#nodesun').val();
                    var nacolor = $('#nacolor').val();
                    var align = $('#align').val();
                    var kegg = $('#kegg').is(":checked");
                    var layer = $('#layer').is(":checked");
                    var gdisc = $('#gdisc').is(":checked");
                    var cdisc = $('#cdisc').is(":checked");
                    var split = $('#split').is(":checked");
                    var expand = $('#expand').is(":checked");
                    var multistate = $('#multistate').is(":checked");
                    var matchd = $('#matchd').is(":checked");
                    var paired = $("#GeneCompare").is(":checked");
                    var geneColumns =  $("#geneColumns").val();
                    var cpdColumns =  $("#NoOfColumns").val();
                    var savedString = "species:" + species + ";geneid:" + geneid + ";compoundid:" + compoundid +
                                        ";glow:" + glow + ";gmid:" + gmid + ";ghigh:" + ghigh + ";clow:" + clow +
                                        ";cmid:" + cmid + ";chigh:" + chigh + ";pathwayList:" + pathwayList + ";suffix:" + suffix +
                                        ";offset:" + offset +";glmt:" + glmt + ";clmt:" +clmt + ";gbins:" +gbins+ ";cbins:" +cbins +
        ";kpos:" + kpos + ";pos:" + pos +";nodesun:" +
         nodesun + ";nacolor:" + nacolor + ";align:" + align + ";kegg:" + kegg + ";layer:" + layer + ";gdisc:" + gdisc + ";cdisc:" + cdisc + ";split:" + split
        + ";expand:" + expand + ";multistate:" + multistate + ";matchd:" + matchd + ";geneCompare:" + paired + ";geneColumns:" + geneColumns +";cmpdColumns:" + cpdColumns ;

                    $('#saveAttributes').val(savedString);
                }


                tab1_array = ["assayData", "cpdassayData", "geneid", "cpdid", "species", "pathway", "pathwayList", "suffix"];
                tab2_array = ["offset"];
                tab3_array = ["glmt", "clmt", "gbins", "cbins"];

                //validation using jquery-validation framework
                var validator = $('#anal_form').validate({

                    invalidHandler: function (form, validator) {
                        var errors = validator.numberOfInvalids();

                        $.each(validator.errorList, function (index, value) {
                            if ($.inArray(value.element.attributes.id.nodeValue + "", tab1_array) > 0) {
                                $('#inputOutput').css('background-color', '#f2dede')
                            } else if ($.inArray(value.element.attributes.id.nodeValue + "", tab2_array) > 0) {
                                $('#graphics').css('background-color', '#f2dede')
                            } else if ($.inArray(value.element.attributes.id.nodeValue + "", tab3_array) > 0) {
                                $('#coloration').css('background-color', '#f2dede')
                            }

                        });

                    },

                    rules: {

                        gfile: {

                            extension: "txt|csv",

                            required: {

                                depends: function (element) {

                                    return $("#cpdassayData").is(':empty');
                                }

                            }

                        },

                        cpdfile: {

                            extension: "txt|csv",

                            required: {

                                depends: function (element) {

                                    return $("#assayData").is(':empty');
                                }

                            }

                        },

                gcheck: {
                 required: {
                                  depends: function(element) {

                        return !$("#cpdcheck").is(':checked');
                    }
                    }
                },


                cpdcheck: {
                    required: {
                        depends: function(element) {
                        return !$("#gcheck").is(':checked');
                        }
                    }

                },

                        geneid: {

                            required: true,
                            geneIdDBMatch: true

                        },

                        cpdid: {
                            required: true,
                            cpdidDBMatch: true
                        },

                        species: {
                            required: true,
                            speciesDBMatch: true,
                            speciesGeneIdMatch: true
                        },

                        pathway: {
                            pathwayIdDBMatch: true
                        },
                        pathwayList: {
                            required: true,
                            ListPathwayMatch: true
                        },

                        suffix: {
                            required: true
                        },

                        offset: {
                            required: true,
                            number: true
                        },

                        glmt: {
                            required: true,
                            lmtFormat: true

                        },

                        clmt: {
                            required: true,
                            lmtFormat: true

                        },

                        gbins: {
                            required: true,
                            digits: true
                        },

                        cbins: {
                            required: true,
                            digits: true
                        }


                    },
                    messages: {
                        gfile: {
                            required: "Upload the Gene Data or Compound Data file.",
                            extension: "Uploaded Gene Data file extension is not supported. Supports only txt/csv."
                        },
                        cpdfile: {
                            required: "Upload the Gene Data or Compound Data file.",
                            extension: "Uploaded Compound Data file extension is not supported. Supports only txt/csv."
                        },
                gcheck: {
                  required: "Any one of the gene data or compound data should be checked"
                },
                cpdcheck: {
                  required: "Any one of the gene data or compound data should be checked"
                },
                        geneid: {
                            required: "Gene ID type is not valid.",
                            geneIdDBMatch: "Gene ID type cannot be empty."
                        },
                        cpdid: {
                            required: "Compound ID type cannot be empty",
                            cpdidDBMatch: "Entered compound ID type does not exist"
                        },
                        species: {
                            required: "Species cannot be left empty",
                            speciesDBMatch: "Species is not valid",
                            speciesGeneIdMatch: "For this Species value Gene ID type  should be either 'ENTREZ' or 'KEGG'."
                        },
                        pathway: {},
                        pathwayList: {
                            required: "At least one Pathway should be selected.",
                            ListPathwayMatch: "Entered Pathway ID is not a valid pathway."
                        },
                        suffix: {
                            required: "Output Suffix cannot be left empty"

                        },
                        offset: {
                            required: "Compound Label Offset value cannot be left empty",
                            number: "Compound Label Offset  value must be numeric"
                        },
                        glmt: {
                            required: "Gene Limit value cannot be left empty",
                            lmtFormat: "Gene Limit value must be numeric values separated by comma",
                            //number: "Gene Limit Value must be numeric"
                        },
                        clmt: {
                            required: "Compound limit  cannot be left empty",
                            lmtFormat: "Compound Limit value must be numeric values separated by comma",
                            //number: "Compound Limit Value must be numeric"
                        },
                        gbins: {
                            required: "Gene Bins cannot be left empty",
                            digits: "Gene bins value must be numeric"
                        },
                        cbins: {
                            required: "Compound bins cannot be left empty",
                            digits: "Compound bins values must be numeric"
                        }


                    }


                });

                jQuery.validator.addMethod('geneIdDBMatch', function (value, element) {
                    console.log(gene_array);
                    return (in_gene_array(gene_array, value));

                });
                jQuery.validator.addMethod('cpdidDBMatch', function (value, element) {
                    console.log(cmpd_array);
                    return (in_cmpd_array(cmpd_array, value));

                });
                jQuery.validator.addMethod('speciesDBMatch', function (value, element) {
                    var species_value = value.split("-")[0];
                    return (in_species_array(species_array, species_value));
                });

                jQuery.validator.addMethod('speciesGeneIdMatch', function (value, element) {

                    var most_spec = ['aga', 'ath', 'bta', 'cel', 'cfa', 'dme', 'dre', 'eco', 'ecs', 'gga', 'hsa', 'mmu', 'mcc', 'pfa', 'ptr', 'rno', 'sce', 'Pig', 'ssc', 'xla'];
                    var most_flag = false;

                    for (var i = 0; i < most_spec.length; i++) {
                        if (value.split("-")[0] == most_spec[i]) {
                            most_flag = true;
                            break;
                        }
                    }
                    if (!most_flag && ( $('#geneid').val().toUpperCase() != 'ENTREZ' && $('#geneid').val().toUpperCase() != 'KEGG' )) {
                        return false;
                    }
                    else {
                        return true;
                    }
                });

                jQuery.validator.addMethod('pathwayIdDBMatch', function (value, element) {
                    if (value == null) {
                        return true;
                    }

                    return true;
                });
                jQuery.validator.addMethod('ListPathwayMatch', function (value, element) {
                    //pathway_array

                    pathwayArray = $('#pathwayList').text().split(',');
                    newCreateArray = "";
                    evenOneTrueFlag = false;
                    $.each(pathwayArray, function (index, value) {
                        if (value != null) {
                            pt_value = $.trim(value);
                            //check existence
                            console.log(""+pt_value);
                            if ($.inArray(pt_value,pathway_array) >-1) {
                                evenOneTrueFlag = true;
                                newCreateArray = newCreateArray + value + ",";
                            }
                        }
                    });
                    console.log(newCreateArray);
                   // $('#pathwayList').text(newCreateArray);
                    return evenOneTrueFlag;
                });


                jQuery.validator.addMethod('lmtFormat', function (value, element) {

                    var lmtArray = value.split(",");
                    var flag = true;
                    if (lmtArray.length <= 2) {
                        $.each(lmtArray, function (index, value1) {
                            if (value1 != null && value1 != "") {
                                var patt = new RegExp("^[1-9]\d*(\.\d+)?$");
                                if (isNaN(value1)) {
                                    flag = false;
                                }
                            }
                        });
                    } else {
                        flag = false;
                    }
                    return flag;

                });
            </script>



