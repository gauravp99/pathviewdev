
<div class="stepsdiv" id="geneid-div" @if (isset(Session::get('err_atr')['geneid'])) style="background-color:#DA6666;" @endif>
    <div class="col-sm-12">
        <div class="col-sm-5">
            <a href="tutorial#gene_id" title="ID type used for the Gene Data. This can be selected from the autosuggest drop down list." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
            </a>
            {!!form::label('geneid','Gene ID Type:')!!}
        </div>
        <div class="col-sm-7">
            <input class="ex8" list="geneidlist" name="geneid" id="geneid" value="<?php echo isset(Session::get('Sess')['geneid']) ? Session::get('Sess')['geneid'] : $geneid ?>" autocomplete="on">
        </div>
    </div>
    <datalist id="geneidlist">
        <!--[if (lt IE 10)]><select disabled style="display:none"><![endif]-->
        <?php
        $gene = DB::table('gene')->get();
        foreach ($gene as $gene1) {
            echo "<option>$gene1->geneid</option>";
        }
        ?>
        <!--[if (lt IE 10)]></select><![endif]-->
    </datalist>
</div>



<div class="stepsdiv" id="cpdid-div" <?php if (isset(Session::get('err_atr')['cpdid'])) { echo "style='background-color:#DA6666;'"; } ?>>
    <div class="col-sm-12">
        <div class="col-sm-5">
            <a href="tutorial#cpd_id" title="ID type used for the Compound Data. This can be selected from the autosuggest drop down list." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
            </a>
            {!!form::label('cpdid','Compound ID Type:') !!}
        </div>
        <div class="col-sm-7">
            <input class="ex8" list="cpdidlist" name="cpdid" id="cpdid" value="<?php echo isset(Session::get('Sess')['cpdid']) ? Session::get('Sess')['cpdid'] : $cpdid ?>" autocomplete="on">
        </div>
    </div>

    <datalist id="cpdidlist">
        <!--[if (lt IE 10)]><select disabled style="display:none"><![endif]-->
        <?php $compound = DB::table('compound')->get(); foreach ($compound as $compound1) { echo "<option>$compound1->cmpdid</option>"; } ?>
        <!--[if (lt IE 10)]></select><![endif]-->
    </datalist>
</div>


<div class="stepsdiv" id="species-div" <?php if (isset(Session::get('err_atr')['species'])) {echo "style='background-color:#DA6666;'";} ?>>
    <div class="col-sm-12">
        <div class="col-sm-5">
            <a href="tutorial#species" title="Either the KEGG code, scientific name or the common name of the target species. Species may also be 'ko' for KEGG Orthology pathways."  target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
            </a>
            {!!form::label('species','Species:') !!}
        </div>
        <div class="col-sm-7">
            <input class="ex8" list="specieslist" name="species" id="species" value="<?php echo isset(Session::get('Sess')['species']) ? Session::get('Sess')['species'] : $species ?>"  autocomplete="on">
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



<div class="stepsdiv" <?php if (isset(Session::get('err_atr')['pathway'])) { echo "style='background-color:#DA6666;'";} ?> id="pat-select">
    <div class="col-sm-12">
        <div class="col-sm-12">
            <div class="col-sm-5" >
                <a href="tutorial#pwy_id" title="KEGG pathway ID(s), usually 5 digit. Can be entered in 2 ways from select box and autosuggest text box."  target="_blank" class="scrollToTop" style="float:left;margin-right:5px;margin-left: -15px">
                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                </a>
                {!!form::label('pathway','Pathway ID:') !!}
            </div>
        </div>

        <div class="col-sm-5">

            <input class="ex8" style="width:100%;" list="pathwaylist" name="pathway" id="pathway1" style="float:none;width: 110%;" value="<?php echo isset(Session::get('Sess')['pathway']) ? Session::get('Sess')['pathway'] : $pathway ?>" autocomplete="on">
            <br/>
            <select name="selectfrom" style="float:none;width:100%;height:236px;font-size: 17px;" id="select-from" multiple="" size="10" class="multiple-select">
                <?php
                $pathway = DB::table('Pathway')->get();

                foreach ($pathway as $pathway1) {
                    echo "<option>" . $pathway1->pathway_id . "-" . $pathway1->pathway_desc . "</option>";
                }
                ?>

            </select>

        </div>

        <div class="col-sm-2" style="margin-left: 20px;">
            <a class="margin-top:-10px" href="JavaScript:void(0);" id="btn-add1" style="font-size:24px;">
                <span style="margin-top: 1px;padding: 6px;box-shadow: 0px 0px 3px #AAA;border: 1px solid #FEF7F7;margin-left: 2px;margin:5px" class="glyphicon glyphicon-plus"></span>
            </a>

            <div class="pathwayiconsep" style="margin-top: 60px">
                </div>


            <a class="margin-top:-10px" href="JavaScript:void(0);" id="btn-add" style="font-size:24px;">
                <span class="glyphicon glyphicon-forward" style="box-shadow: 0px 0px 3px #AAA;border: 1px solid rgb(254, 247, 247);padding: 6px;margin:5px"></span>
            </a>

        </div>

        <div class="col-sm-5" style="margin-left: -20px;">
            <textarea id="selecttextfield" name="selecttextfield" wrap="off" style="resize: none;float:none;width:100%;height:110%;font-size:16px;margin-left: 5px;" rows="11" cols="14" ><?php echo isset(Session::get('Sess')['selecttextfield']) ? Session::get('Sess')['selecttextfield'] : $selectpath; ?></textarea>
        </div>
    </div>
    <datalist id="pathwaylist">
        <!--[if (lt IE 10)]><select disabled style="display:none"><![endif]-->
        <?php
        $pathway = DB::table('Pathway')->get();
        foreach ($pathway as $pathway1) {
            echo "<option>" . $pathway1->pathway_id . "-" . $pathway1->pathway_desc . "</option>";
        }
        ?>
        <!--[if (lt IE 10)]></select><![endif]-->
    </datalist>
</div>



<div id="suffix-div" class="stepsdiv" <?php if (isset(Session::get('err_atr')['suffix'])) {echo "style='background-color:#DA6666;'";} ?> @if ($errors->has('suffix')) style="background-color:#DA6666;" @endif>
    <div class="col-sm-12">
        <div class="col-sm-5">
            <a href="tutorial#suffix" title="The suffix to be added after the pathway name as part of the output graph file name." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
            </a>
            {!!form::label('suffix','Output Suffix:') !!}
        </div>
        <div class="col-sm-7">
            <input type="text" class="ex8" name="suffix" id="suffix" value="<?php echo isset(Session::get('Sess')['suffix']) ? Session::get('Sess')['suffix'] : $suffix ?>">
        </div>
    </div>
</div>

</fieldset>
<fieldset class="step">
    <div class="stepsdiv" id="kegg-layer-div">
        <div class="col-sm-12">
            <div class="col-sm-6">
                <a href="tutorial#kegg" title="Whether to render the pathway as native KEGG graph (.png) or using Graphviz layout engine (.pdf). " target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                </a>
                {!!form::label('kegg','Kegg Native:') !!}
                <input name="kegg" value="T" id="kegg" <?php if (isset(Session::get('Sess')['kegg'])) {echo "checked";} else {if ((Session::get('Sess') == NULL)) { if($kegg) {echo "checked";}}}?> type="checkbox" style="margin-left: 10px;">
            </div>
            <div class="col-sm-6">
                <a href="tutorial#layer" title="Controls plotting layers: 1) if node colors be plotted in the same layer as the pathway graph when Kegg Native is checked, 2) if edge/node type legend be plotted in the same page when Kegg Native is unchecked. " target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
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
                <a href="tutorial#desc" title="Whether gene data should be treated as discrete." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                </a>
                {!!form::label('gdesc',' Descrete Gene:') !!}
                <input name="gdisc" value="T" id="gdisc"
                <?php if (isset(Session::get('Sess')['gdisc'])) {echo "checked";} else {if ((Session::get('Sess') == NULL)) { if($gdisc) {echo "checked";}}}?> type="checkbox" style="margin-left: 10px;">
            </div>
            <div class="col-sm-6">
                <a href="tutorial#desc" title="Whether compound data should be treated as discrete." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
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
                <a href="tutorial#split" title="Whether split node groups are split to individual nodes." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"> </span>
                </a>
                {!!form::label('split','Split Group:') !!}
                <input name="split" value="T" id="split"<?php if (isset(Session::get('Sess')['split'])) {echo "checked";} else {if (Session::get('Sess') == NULL) {if($split) {echo "checked";}}}?> type="checkbox" style="margin-left: 10px;">
            </div>
            <div class="col-sm-6">
                <a href="tutorial#expand" title="Whether the multiple-gene nodes are expanded into single-gene nodes" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
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
                <a href="tutorial#multi" title="Whether multiple states (samples or columns) gene data or compound data should be integrated and plotted in the same graph." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                </a>
                {!!form::label('multistate','Multi State:') !!}
                <input name="multistate" value="T" id="multistate"<?php if (isset(Session::get('Sess')['multistate'])) {echo "checked";} else {if (Session::get('Sess') == NULL) {if($multistate) {echo "checked";}}}?> type="checkbox" style="margin-left: 10px;">
            </div>
            <div class="col-sm-6">
                <a href="tutorial#match" title="Whether the samples of gene data and Compound data are paired" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
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
                <a href="tutorial#clabel" title="How much compound labels should be put above the default position or node center." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;"><span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span></a>
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
                <a href="tutorial#kalign" title="How the color keys are aligned when both Gene Data and Compound Data are not NULL." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
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
                <a href="tutorial#spos" title="Controls the position of pathview signature." target="_blank" class="scrollToTop" style="float:left;margin-right:5px">
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
                <a href="tutorial#kpos" title="Controls the position of color key(s)." target="_blank" class="scrollToTop" style="float:left;margin-right:5px">
                    <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span>
                </a>
                <div class="col-sm-6" style="margin-left: -20px;">
                    {!!form::label('kpos','Key Position:') !!}
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
                <a href="tutorial#nsum" title="The method name to calculate node summary given that multiple genes or compounds are mapped to it." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
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
                <a href="tutorial#ncolor" title="Color used for NA's or missing values in Gene Data and Compound Data." target="_blank" class="scrollToTop" style="float:left;">
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
                <a href="tutorial#limit" title="The limit values for Gene Data and Compound Data when converting them to pseudo colors.This field is a numeric field you can enter two values separated by a comma for example 1,2." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
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
                <a href="tutorial#bins" title="This argument specifies the number of levels or bins for Gene Data and Compound Data when converting them to pseudo colors." target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
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
                <a href="tutorial#color" title="These arguments specify specifies the color spectra to code Gene Data and Compound Data" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
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
                <a href="tutorial#color" title="These arguments specify specifies the color spectra to code Gene Data and Compound Data" target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
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
                <a href="tutorial#color"
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
<div class="steps">
    <input type="submit" id="submit-button" class="btn btn-primary" style="font-size: 20px;width: 30%;margin-left: 15%;;margin-top: 10px;float:left;" value="Submit" onclick="return fileCheck()"/>
    <input type="Reset" id="reset" class="btn btn-primary" style="font-size: 20px;width: 30%;margin-left:10%;margin-top: 10px;;float:left;" value="Reset" onclick="return reset()"/>
</div>
{!! form::close() !!}
</div>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div style="opacity:0.3; background: #006980;width: 100%;height:100%">
        <img class="first-slide" style="margin-top: 10%;width:20%"
             src="images/loading.gif">
    </div>

</div>


