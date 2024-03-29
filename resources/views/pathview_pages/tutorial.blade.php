@extends('app')


@section('content')

@if (!isset($_GET['instruction_flag']))
   @include('navigation')
    <div class='col-md-2-result sidebar col-md-offset-2'>
@endif
    <div class="col-md-12 content" style="text-align: center;">

        <h1><b>Help Information</b></h1>


        <div class="col-md-12 content">
            <h1 class="arg_content">Custom Analysis</h1>

            <div class="col-lg-4">
                <a href="#Input"><img class="img-circle content" src="/images/file_upload.png" alt="Input & output"
                                      height="140" width="140"></a>

                <h2>Input & Output</h2>

                <p>Upload your gene and/or compound data, specify species, pathways, ID type etc. </p>


            </div>

            <!-- /.col-lg-4 -->

            <div class="col-lg-4">
                <a href="#Graphics"><img class="img-circle" src="/images/graphics.png" alt="Graphics" height="140"
                                         width="140"></a>

                <h2>Graphics</h2>

                <p>Specify the layout, style, and node/edge or legend attributes of the output graphs.</p>


            </div>
            <!-- /.col-lg-4 -->
            <div class="col-lg-4">
                <a href="#Coloration"><img class="img-circle" src="/images/color.png" alt="Colouration" height="140"
                                           width="140"></a>

                <h2>Coloration</h2>

                <p>Customize the color coding of your gene and compound data.</p>


            </div>
            <!-- /.col-lg-4 -->
        </div>
        <div class="col-md-12 content">
            <div class="col-md-4">
                <div class="list-group">

                    <ul class="nav navbar-nav" style="width:100%">
                        <li class="dropdown" style="width:100%;margin-bottom: 10px;">
                            <a href="#" class="dropdown-toggle list-group-item active"
                               data-toggle="dropdown">Options<span
                                        class="glyphicon glyphicon-download pull-right"></span></a>
                            <ul class="dropdown-menu" style="width:100%">

                                <li style="width:300px;"><a href="#gene_data">Gene Data</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#cpd_data">Compound Data</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#gene_id">Gene ID Type</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#cpd_id">Compound ID Type</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#species">Species</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#pwy_id">Pathway Selection</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#pwy_id">Pathway ID</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#suffix">Output Suffix</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-md-4">
                <div class="list-group">

                    <ul class="nav navbar-nav" style="width:100%">
                        <li class="dropdown" style="width:100%;margin-bottom: 10px;">
                            <a href="#" class="dropdown-toggle list-group-item active"
                               data-toggle="dropdown">Options<span
                                        class="glyphicon glyphicon-download pull-right"></span></a>
                            <ul class="dropdown-menu" style="width:100%">

                                <li style="width:300px;"><a href="#kegg">Kegg Native</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#layer">Same Layer</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#desc">Discrete</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#split">Split Group</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#expand">Expand Node</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#multi">Multi State</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#match">Match Data</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#clabel">Compound Label Offset</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#kalign">Key Alignment</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#spos">Signature Position</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#kpos">Key Position</a></li>

                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="list-group">

                    <ul class="nav navbar-nav" style="width:100%">
                        <li class="dropdown" style="width:100%;margin-bottom: 10px;">
                            <a href="#" class="dropdown-toggle list-group-item active"
                               data-toggle="dropdown">Options<span
                                        class="glyphicon glyphicon-download pull-right"></span></a>
                            <ul class="dropdown-menu" style="width:100%">

                                <li style="width:300px;"><a href="#nsum">Node Sum</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#ncolor">NA Color</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#limit">Limit</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#bins">Bins</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#color">Low</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#color">Mid</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#color">High</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <br/>
        <br/>

        <br/>
        <br/>
        <br/>
        <br/>

        <div class="col-md-12"></div>
        <div class="col-md-12 content">

            <h1 class="arg_content">Example Analysis</h1>

            <div class="col-md-6">
                <div style="height:70px"><h2 style="text-align:left">Multiple Sample KEGG View</h2></div>

                <p>This example shows the multiple sample/state integration with Pathview KEGG view.</p>

                <p><a class="btn btn-default" href="api_examples#example1" role="button">View details »</a></p>
            </div>
            <div class="col-md-6" >
                <div style="height:70px"><h2 style="text-align:left">Multiple Sample Graphviz View</h2></div>

                <p>This example shows the multiple sample/state integration with Pathview Graphviz view. </p>

                <p><a class="btn btn-default" href="api_examples#example2" role="button">View details »</a></p>
            </div>

        </div>
        <div class="col-md-12 content">
            <div class="col-md-6">
                <div style="height:70px"><h2 style="text-align:left">ID Mapping</h2></div>

                <p>This example shows the ID mapping capability of Pathview.</p>

                <p><a class="btn btn-default" href="api_examples#example3" role="button">View details »</a></p>
            </div>
            <div class="col-md-6">
                <div style="height:70px"><h2 style="text-align:left">Integrated Pathway Analysis </h2></div>

                <p>This example covers an integration pathway analysis workflow based on Pathview.</p>

                <p><a class="btn btn-default" href="api_examples#example4" role="button">View details »</a></p>
            </div>
        </div>
        <div class="col-sm-12">

            <section id="results">

                <h1 class="arg_content">Results</h1>

                <p class="content1">
                       <a href="/overview">Detailed result description here</a> 
                    </p>
            </section>

        </div>
        <div class="col-sm-12">
            <section id="input">
                <h1 class="arg_content">User Options: Input and Output</h1>

                <div class="page-header col-sm-12">
                    <dl>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="gene_data">Gene Data</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd><p>Gene Data accepts data matrices in tab- or comma-delimited format (txt or csv). Data
                                    matrix has genes as rows and samples as columns. First column should be gene IDs,
                                    first row sample IDs. The data may also be a single-column of gene IDs (<a href="data/entrezGeneList.human.txt" target=_blank>example</a>). Here gene ID
				    is a generic concept, including multiple types of 
                                    transcript or protein IDs, for example ENTREZ Gene, Symbol, RefSeq, GenBank Accession Number, 
                                    UNIPROT, Enzyme Accession Number, etc. Users can specify this information through the Gene ID Type option below.
                                    uniquely mappable to KEGG gene IDs. KEGG ortholog IDs are also treated as gene IDs
                                    as to handle metagenomic data.</p>

                                    <p>Both the absolute or original expression levels and the relative expression levels (log2 fold changes, t-statistics) can be visualized on pathways. However, the latter are more frequently used.
                                    If you supply data as original expression levels, but you want to visualize the relative expression levels (or differences) between two states. You need to specify a few extra options(NOT needed if you just want to visualize the input data as it is):
                                    <ul class="dataList" style="margin-left:30px;">


                                            <li>
                                        <dt name="control_reference"> <b>Control/reference:</b></dt> <dd>the column numbers for controls;</dd>
                                                </li>




                                        <li >
                                        <dt name="control_sample"><b>Case/sample:</b> </dt><dd>the column numbers for cases;</dd>
                                        </li>




                                        <li >
                                        <dt name="compare"><b>Compare:</b></dt> <dd>whether the experiment samples are paired or not.</dd>
                                        </li>


                                    </ul>


                                    </p>

                                    <p>For examples of gene data, check: <a
                                            href="data/gse16873.d3.txt" target="_balnk">Example Gene Data
                                        1</a> and <a href="data/gse16873.3.txt" target="_balnk">Example Gene
                                        Data 2</a>.</p>
                                    <p>
				      If you intend to do a full pathway analysis plus data visualization (or integration), you need to set 
                                      Pathway Selection below to Auto. Gene Data and/or Compound Data will also be taken as the input data 
                                      for pathway analysis. Frequently, you also need to the extra options: Control/reference, Case/sample,  
                                      and Compare in the dialogue box. However, these options are NOT needed if your data is already relative  
                                      expression levels or differential scores (log ratios or fold changes). <a href=/api_examples#example4> Example 4</a> covers the full pathway analysis.
                                                                         </p>
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="cpd_data">Compound Data</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>Compound Data accepts data matrices in tab- or comma-delimited format (txt or csv).
                                    The format is the same as Gene Data in format (and you may also need to specify sample columns and experiment design), except rows are compounds including
                                    metabolites, drugs, small molecules etc. For example, check: <a
                                            href="data/sim.cpd.data2.txt" target="_balnk">Example Compound
                                        Data 1</a> and <a href="data/sim.cpd.data1.csv" target="_balnk">Example
					Compound Data 2.</a>
                                   </br>
                                   Like the gene concept, here the compound concept is also generic, including multiple types of metabolites, drugs, small molecules. Users can specify this information through the Compound ID Type option below.
</dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="gene_id">Gene ID Type</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>ID type used for the Gene Data. This can be selected from the autosuggest drop down
                                    list.
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="cpd_id">Compound ID Type</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>ID type used for the Compound Data. This can be selected from the autosuggest drop
                                    down list.
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="species">Species</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>Either the KEGG code, scientific name or the common name of the target species.
                                    Species may also be "ko" for KEGG Orthology pathways. This can be selected from the
                                    autosuggest drop down list.
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="auto_sel">Pathway Selection</a></dt>
                            </div>
                            <div class="panel-body argument">
				<dd>Whether the target pathways for visualization be selected automatically or specified by the user. 
                                    Auto-selection is recommended if the user is not sure what pathway(s) to view. Pathways are selected 
                                    using GAGE for continuous data or over-representation test for discrete data (i.e. list of gene or compound IDs). 
				    If no pathways are called significant, the few top pathways will be selected.When both gene data and compound data 
                                    are present, pathway analysis is done on the two datasets separately first, then the results are combined into more 
                                    robust global statistics/p-values through meta-analysis. You may either <a href="api_examples#example4">check</a> or <a href=/example4 >try</a> Example 4 to see the effect 
                                    of setting this option.
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="pwy_id">Pathway ID</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>KEGG pathway ID(s), usually 5 digit. Can be entered in 2 ways from select box and
                                    autosuggest text box.This option is not needed when Pathway Selection option is set to Auto.
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="suffix">Output Suffix</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>The suffix to be added after the pathway name as part of the output graph file name.
                                    Sample names or column names of the Gene Data or Compound Data are also added when
                                    there are
                                    multiple samples.
                                </dd>
                            </div>
                        </div>
                    </dl>
                </div>

            </section>
        </div>
        <div class="col-sm-12">
            <section id="Graphics">
                <h1 class="arg_content">User Options: Graphics</h1>


                <div class="page-header col-sm-12">
                    <dl>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="kegg">Kegg Native</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd> Whether to render the pathway as native KEGG graph (.png) or using Graphviz
                                    layout
				    engine (.pdf).
                                    Note Graphviz view may drop nodes due to missing data in KEGG xml data files.
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="layer">Same Layer</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd> Controls plotting layers: 1) if node colors be plotted
                                    in
                                    the same layer as the pathway graph when Kegg Native is checked, 2) if edge/node
                                    type
                                    legend be plotted in the same page when Kegg Native is unchecked.
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="desc">Discrete (Gene and Compound)</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>Whether Gene Data or Compound Data should be treated as
                                    discrete.
                                    Default values are both FALSE, i.e. both data should be treated as continuous.
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="kalign">Keys Alignment</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>How the color keys are aligned when both Gene Data and Compound Data are
                                    not
                                    NULL. Potential values are "x", aligned by x coordinates, and "y", aligned by y
                                    coordinates.
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="split">Split Group</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>Whether split node groups are split to individual nodes. Each split member nodes
                                    inherits all edges from the node group. This option only affects Graphviz graph
                                    view,
                                    i.e. when Kegg Native is FALSE. This option also effects most metabolic pathways
                                    even
                                    without group nodes defined originally. For these pathways, genes involved in the
                                    same
                                    reaction are grouped automatically when converting reactions to edges unless split
                                    group
                                    is TRUE. Default value is FALSE.
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="expand">Expand Node</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>Whether the multiple-gene nodes are expanded into single-gene nodes. Each expanded
                                    single-gene nodes inherits all edges from the original multiple gene node. This
                                    option
                                    only affects Graphviz graph view, i.e. when Kegg Native is FALSE. This option is not
                                    effective for most metabolic pathways where it conflicts with converting reactions
                                    to
                                    edges. Default value is FALSE.
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="multi">Multi State</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>Whether multiple states (samples or columns) Gene Data or Compound Data should be
                                    integrated and plotted in the same graph. Default is TRUE, In other
                                    words,
                                    gene or compound nodes will be sliced into multiple pieces corresponding to the
                                    number
                                    of states in the data.
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="match">Match Data</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>Whether the samples of Gene Data and Compound Data are paired. Default match data is
                                    TRUE. When let sample sizes of Gene Data and Compound Data be m and n, when m>n,
                                    extra
                                    columns of NA’s (mapped to no color) will be added to Compound Data as to make the
                                    sample size the same. This will result in the same number of slice in gene nodes and
                                    compound when multi state is TRUE.
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="spos">Signature Position</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>Controls the position of pathview signature. Default value is "bottom right". No
                                    pathview signature will be put when "None" is selected.
                                    Potential
                                    values can be found in the drop down list.
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="kpos">Key Position</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>Controls the position of color key(s). Default value is "top left". No color key
                                    will be plot when "None" is selected. Potential values
                                    can
                                    be found in the drop down list.
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="clabel">Compound Label Offset</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>How much compound labels should be put above the default position or node
                                    center. This is useful when compounds are labeled by full name, which affects the
                                    look
                                    of compound nodes and color. Only effective when Kegg Native is FALSE.
                                </dd>
                            </div>
                        </div>
                    </dl>
                </div>
            </section>
        </div>
        <div class="col-sm-12">
            <section id="Coloration">
                <h1 class="arg_content">User Options: Coloration</h1>


                <div class="page-header col-sm-12">
                    <dl>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="nsum">Node Sum</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>The method name to calculate node summary given that multiple genes or compounds are
                                    mapped to it. Potential values can be found in the drop down list. Default Value is
                                    "Sum".
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="ncolor">NA Color</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>Color used for NA's or missing values in Gene Data and Compound Data. Potential
                                    value
                                    can be
                                    "transparent" or "grey".
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="limit">Limit (Gene and Compound)</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>The limit values for Gene Data and Compound Data when
                                    converting them to pseudo colors. This field is a numeric field you can enter two
                                    values separated by a comma for example "1,2" (without quote). First value stands
                                    for lower limit and second
                                    value for higher limit. If a single value n is given then limit is taken as (-n, n).
                                    Input fields are enabled after checking respective
                                    checkpoints for Gene and Compound Data.
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="bins">Bins (Gene and Compound)</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>This argument specifies the number of levels or bins for Gene Data and Compound Data
                                    when converting them to pseudo colors. Default value is 10.
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="color">Low, Mid, High (Gene and Compound)</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>These arguments specify the color spectra to code Gene Data and Compound
                                    Data.
                                    Default spectra (low-mid-high) "green-gray-red" and "blue-gray-yellow" are
                                    used
                                    for Gene Data and Compound Data respectively. Users may specify colors using common
                                    names (green, red etc), hex color codes (00FF00, D3D3D3 etc), or the color picker.
                                </dd>
                            </div>
                        </div>

                    </dl>
                </div>
            </section>


        </div>
        <div class="col-sm-12">
            <section id="refrence">
                <h1 class="arg_content" >Reference</h1>
                <div style="text-align: left;">
                @include('reference')
                </div>
            </section>
        </div>

        <div class="col-sm-12">

            <section id="contact">

                <h1 class="arg_content">Contact</h1>

                <p class="contact">Email us: <a href="mailto:pathomics@gmail.com">pathomics@gmail.com</a></p>
            </section>

        </div>

    </div>
    <div class="scroll">
        <a href="#" class="scrollToTop"><span class="glyphicon glyphicon-menu-up"
                                              style="font-size: 30px; margin-left: 100px;"></span></a></div>
 </div>
    <style>
        .scrollToTop {
            width: 100px;
            height: 130px;
            padding: 10px;
            text-align: center;
            background: whiteSmoke;
            font-weight: bold;
            color: #444;
            text-decoration: none;
            position: fixed;
            top: 75px;
            right: 40px;
            display: none;
            font-size: 50px;

            background: url("/images/icontop.png") no-repeat 0px 20px;
        }

        .scrollToTop:hover {
            text-decoration: none;
        }
    </style>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script>
        $(document).ready(function () {

            //Check to see if the window is top if not then display button
            $(window).scroll(function () {
                if ($(this).scrollTop() > 100) {
                    $('.scrollToTop').fadeIn();
                } else {
                    $('.scrollToTop').fadeOut();
                }
            });

            //Click event to scroll to top
            $('.scrollToTop').click(function () {
                $('html, body').animate({scrollTop: 0}, 800);
                return false;
            });

        });
    </script>

@stop
