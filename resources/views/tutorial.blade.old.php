
@extends('app')

@section('content')


    <div class="col-md-12 content" style="text-align: center;">

        <h1><b>Help Information</b></h1>

        <h1 class="arg_content">Custom Analysis</h1>

        <div class="row">
            <div class="col-lg-4">
                <a href="#Input"><img class="img-circle content" src="/images/file_upload.png" alt="Input & output"
                                      height="140" width="140"></a>

                <h2>Input & Output</h2>

                <p>Upload your gene and/or compound data, specify species, pathways, ID type etc. </p>

                <div class="list-group">

                    <ul class="nav navbar-nav" style="width:100%">
                        <li class="dropdown" style="width:100%">
                            <a href="#" class="dropdown-toggle list-group-item active"
                               data-toggle="dropdown">Options<span
                                        class="glyphicon glyphicon-download pull-right"></span></a>
                            <ul class="dropdown-menu" style="width:100%">

                                <li style="width:300px;"><a href="#gene_data">Gene Data</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#cpd_data">Compound Data</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#gene_id">Gene Id</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#cpd_id">Compound ID</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#species">Species</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#pwy_id">Pathway</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#suffix">Suffix</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- /.col-lg-4 -->

            <div class="col-lg-4">
                <a href="#Graphics"><img class="img-circle" src="/images/graphics.png" alt="Graphics" height="140"
                                         width="140"></a>

                <h2>Graphics</h2>

                <p>Specify the layout, style, and node/edge or legend attributes of the output graphs.</p>

                <div class="list-group">

                    <ul class="nav navbar-nav" style="width:100%">
                        <li class="dropdown" style="width:100%">
                            <a href="#" class="dropdown-toggle list-group-item active"
                               data-toggle="dropdown">Options<span
                                        class="glyphicon glyphicon-download pull-right"></span></a>
                            <ul class="dropdown-menu" style="width:100%">

                                <li style="width:300px;"><a href="#kegg">Kegg Native</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#layer">Same Layer</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#desc">Descrete</a></li>
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
            <!-- /.col-lg-4 -->
            <div class="col-lg-4">
                <a href="#Coloration"><img class="img-circle" src="/images/color.png" alt="Colouration" height="140"
                                           width="140"></a>

                <h2>Coloration</h2>

                <p>Customize the color coding of your gene and compound data.</p>

                <div class="list-group">

                    <ul class="nav navbar-nav" style="width:100%">
                        <li class="dropdown" style="width:100%;margin-top: 24px;">
                            <a href="#" class="dropdown-toggle list-group-item active"
                               data-toggle="dropdown">Options<span
                                        class="glyphicon glyphicon-download pull-right"></span></a>
                            <ul class="dropdown-menu" style="width:100%">

                                <li style="width:300px;"><a href="#nsum">Node Summary</a></li>
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
            <!-- /.col-lg-4 -->
        </div>
        <div class="col-sm-12">
            <h1 class="arg_content">Example Analysis</h1>


            <div class="col-md-4">
                <h2>Multiple Sample KEGG view</h2>

                <p>This example shows the multiple sample/state integration with Pathview KEGG view.</p>

                <p><a class="btn btn-default" href="#example1" role="button">View details »</a></p>
            </div>
            <div class="col-md-5">
                <h2>Multiple Sample Graphviz view</h2>

                <p>This example shows the multiple sample/state integration with Pathview Graphviz viewo. </p>

                <p><a class="btn btn-default" href="#example2" role="button">View details »</a></p>
            </div>
            <div class="col-md-3">
                <h2>ID Mapping</h2>

                <p>This example shows the ID mapping capability of Pathview.</p>

                <p><a class="btn btn-default" href="#example3" role="button">View details »</a></p>
            </div>
        </div>
        <div class="col-sm-12">
            <section id="Input">
                <h1 class="arg_content">Input and Output</h1>

                <div class="page-header col-sm-12">
                    <dl>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="gene_data">Gene Data</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>Gene data typically accepts csv, txt file</dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="cpd_data">Compound Data</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>Compound gene data accepts csv, txt file</dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="gene_id">Gene ID Type</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>ID type used for the Gene Data. This can be selected from the drop down.
                                    (Suggestions
                                    are shown on entering the initial letters)
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="cpd_id">Compound ID Type</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>ID type used for the Compound Data. This can be selected from the drop down.
                                    (Suggestions are shown on entering the initial letters)
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="pwy_id">Pathway ID</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>The KEGG pathway ID, usually 5 digit. Can be entered in 2 ways from select box and
                                    text box.
                                    (Suggestions are shown on entering the initial letters). Multiple Pathway IDs can be
                                    entered by clicking the “Add(+) and >> ” button.
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="species">Species</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>Either the KEGG code, scientific name or the common name of the target species. When
                                    KEGG ortholog pathway is considered, species is "ko". Default value for species is
                                    "hsa". (Suggestions are shown on entering the initial letters)
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="suffix">Output Suffix</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>The suffix to be added after the pathway name as part of the output graph file.
                                    Sample
                                    names or column names of the Gene Data or Compound Data are also added when there
                                    are
                                    multiple samples. Default value is "pathview".
                                </dd>
                            </div>
                        </div>
                    </dl>
                </div>

            </section>
        </div>
        <div class="col-sm-12">
            <section id="Graphics">
                <h1 class="arg_content">Graphics</h1>


                <div class="page-header col-sm-12">
                    <dl>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="kegg">Kegg Native</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd> Whether to render pathway graph as native KEGG graph (.png) or using graphviz
                                    layout
                                    engine (.pdf). Default is set to TRUE.
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="layer">Same Layer</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd> Can either be TRUE or FALSE. Controls plotting layers: 1) if node colors be plotted
                                    in
                                    the same layer as the pathway graph when kegg native is TRUE, 2) if edge/node type
                                    legend be plotted in the same page when kegg native is FALSE.
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="desc">Discrete</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>This argument tells whether gene data or compound data should be treated as
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
                                <dd>Controls how the color keys are aligned when both Gene Data and Compound Data are
                                    not
                                    NULL. Potential values are "x", aligned by x coordinates, and "y", aligned by y
                                    coordinates. Default value is "x".
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="split">Split Group</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>Whether split node groups are split to individual nodes. Each split member nodes
                                    inherits all edges from the node group. This option only affects graphviz graph
                                    view,
                                    i.e. when kegg native is FALSE. This option also effects most metabolic pathways
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
                                    only affects graphviz graph view, i.e. when kegg native is FALSE. This option is not
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
                                <dd>Whether multiple states (samples or columns) gene data or compound data should be
                                    integrated and plotted in the same graph. Default match data is TRUE, In other
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
                                <dd>Whether the samples of gene data and Compound data are paired. Default match data is
                                    TRUE. When let sample sizes of gene data and compound data be m and n, when m>n,
                                    extra
                                    columns of NA’s (mapped to no color) will be added to Compound data as to make the
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
                                <dd>Controls the position of pathview signature. Default value is “bottomright”.
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
                                <dd>Controls the position of color key(s). Default value is “topleft”. Potential values
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
                                <dd>Specifies how much compound labels should be put above the default position or node
                                    center. This is useful when compounds are labeled by full name, which affects the
                                    look
                                    of compound nodes and color. Only effective when kegg native is FALSE.
                                </dd>
                            </div>
                        </div>
                    </dl>
                </div>
            </section>
        </div>
        <div class="col-sm-12">
            <section id="Coloration">
                <h1 class="arg_content">Coloration</h1>


                <div class="page-header col-sm-12">
                    <dl>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="nsum">Node Sum</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>The method name to calculate node summary given that multiple genes or compounds are
                                    mapped to it. Potential values can be found in the drop down list. Default Value is
                                    “Sum”
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="nclolor">NA Color</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>Color used for NA’s or missing values in Gene Data and Compound Data. Default value
                                    is
                                    “transparent”.
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="limit">Limit</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd> This argument specifies the limit values for Gene Data and Compound Data when
                                    converting them to pseudo colors.This field is a numeric field you can enter two
                                    values separated by a comma for example(1,2). first value for lower limit and second
                                    value is for higher limit. If a single value is given then limit is taken from -n to
                                    n.
                                    Input fields are enabled after checking respective
                                    checkpoints for Gene and Compound Data
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="bins">Bins</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>This argument specifies the number of levels or bins for Gene Data and Compound Data
                                    when converting them to pseudo colors. Default value is 10
                                </dd>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading argument">
                                <dt><a name="color">Low, Mid, High</a></dt>
                            </div>
                            <div class="panel-body argument">
                                <dd>These arguments specify specifies the color spectra to code Gene Data and Compound
                                    Data.
                                    Default spectra (low-mid-high) "green"-"gray"-"red" and "blue"- "gray"-"yellow" are
                                    used
                                    for Gene Data and Compound Data respectively.
                                </dd>
                            </div>
                        </div>

                    </dl>
                </div>
            </section>


        </div>
        <div class="col-sm-12">

            <section id="example1">


                <h1 class="arg_content">Example 1: Multiple sample KEGG view</h1>
                <p>
                    This example shows the capability of multiple sample data handling in Pathview. Data files are pre
                    loaded and All the parameters have been preset.
                    Data files used in this example are <a href="all/demo/example/gse16873.d3.txt" target="_balnk">Gene
                        Data</a> and <a href="all/demo/example/sim.cpd.data2.csv" target="_balnk">Compound Data</a>.
                    In this example gene data has 3 samples and compound data has 2 samples.

                </p>

                <div class="col-sm-8">

                    <img src="all/demo/example/hsa00640.multistate.kegg.multi.png" style="width: 99%;">
                </div>
                <div class="col-sm-4">
                    <ul class="list-group">
                        <li class="list-group-item disabled">
                            Applied Arguments
                        </li>
                        <li class="list-group-item">Pathway ID : 00640-Propanoate metabolism</li>
                        <li class="list-group-item">Species : hsa</li>
                        <li class="list-group-item">Kegg Native : TRUE</li>
                        <li class="list-group-item">Same Layer : TRUE</li>
                        <li class="list-group-item">Multi State : TRUE</li>
                        <li class="list-group-item">Match Data : FALSE</li>
                        <li class="list-group-item">keys Align : y</li>
                    </ul>
                    <a href="/example1">
                        <button type="button" class="btn btn-primary btn-lg GetStarted  " data-toggle="modal">
                            Try It
                        </button>
                    </a>
                </div>
            </section>

        </div>

        <div class="col-sm-12">

            <section id="example2">
                <h1 class="arg_content">Example 2: Multiple sample Graphviz view</h1>

                <p>
                    This example shows the capability of multiple sample data handling in Pathview. Data files are pre
                    loaded and All the parameters have been preset.
                    Data files used in this example are <a href="all/demo/example/gse16873.d3.txt" target="_balnk">Gene
                        Data</a> and <a href="all/demo/example/sim.cpd.data2.csv" target="_blank">Compound Data</a>.
                    In this example gene data has 3 samples and compound data has 2 samples. Difference on this example
                    is
                    you are generating a Graphviz view
                    by making the kegg view to false


                </p>

                <div class="col-sm-8">
                    <img src="all/demo/example/file-page1.png" style="width: 99%;">

                </div>
                <div class="col-sm-4">
                    <ul class="list-group">
                        <a class="list-group-item disabled">
                            Applied Arguments
                        </a>
                        <li class="list-group-item">Pathway ID : 00640-Propanoate metabolism</li>
                        <li class="list-group-item">Species : hsa</li>
                        <li class="list-group-item">Kegg Native : FALSE</li>
                        <li class="list-group-item">Same Layer : TRUE</li>
                        <li class="list-group-item">Multi State : TRUE</li>
                        <li class="list-group-item">Match Data : FALSE</li>
                        <li class="list-group-item">keys Align : y</li>
                        <li class="list-group-item">Gene Limit : 1(min),2(max)</li>

                    </ul>
                    <a href="/example2">
                        <button type="button" class="btn btn-primary btn-lg GetStarted  " data-toggle="modal">
                            Try it
                        </button>
                    </a>
                </div>
            </section>

        </div>

        <div class="col-sm-12">

            <section id="example3">

                <h1 class="arg_content">Example 3: ID Mapping</h1>

                <p>
                    Here's an example showing the ID mapping capability of pathview. Data files are pre loaded and All
                    the
                    parameters have been preset including Gene ID type and Compound ID type.
                    Data files used in this example are <br/><a href="all/demo/example/gene.ensprot.txt"
                                                                target="_blank">Gene
                        Data</a> and <a href="all/demo/example/cpd.cas.csv" target="_blank">Compound Data</a>.

                </p>

                <div class="col-sm-8">
                    <img src="all/demo/example/hsa00640.IDMapping.png" style="width: 99%;">
                </div>
                <div class="col-sm-4">
                    <ul class="list-group">
                        <a class="list-group-item disabled">
                            Applied Arguments
                        </a>
                        <li class="list-group-item">Pathway ID: 00640-Propanoate metabolism</li>
                        <li class="list-group-item">Species : hsa</li>
                        <li class="list-group-item">Gene ID: ENSEMBLPROT</li>
                        <li class="list-group-item">Compound ID: CAS Registry Number</li>
                        <li class="list-group-item">Kegg Native : TRUE</li>
                        <li class="list-group-item">Same Layer : TRUE</li>
                        <li class="list-group-item">keys Align : y</li>
                        <li class="list-group-item">Limit Gene:3 Compound:3</li>
                        <li class="list-group-item">Bins Gene:6 Compound:6</li>

                    </ul>
                    <a href="/example3">
                        <button type="button" class="btn btn-primary btn-lg GetStarted  " data-toggle="modal">
                            Try It
                        </button>
                    </a>
                </div>
            </section>

        </div>

    </div>
    <div class="scroll">
        <a href="#" class="scrollToTop"><span class="glyphicon glyphicon-menu-up"
                                              style="font-size: 30px; margin-left: 100px;"></span></a></div>
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