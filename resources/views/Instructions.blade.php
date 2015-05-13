@extends('app')

@section('content')
    @include('navigation')

    <h1> Intructions </h1>
    <h1 align="center">Pathview Web Interface</h1>
    <div id="table-of-contents">
        <h2>Table of Contents</h2>

        <div id="text-table-of-contents">
            <ul>
                <li><a href="#sec-1">I Examples </a></li>
                <li><a href="#sec-2">II Tutorial </a></li>


            </ul>
        </div>
    </div>

    <h2><a name="sec-1">Examples</a></h2>
    <h3>1.Multiple Sample Data</h3>
    This example shows the capability of multiple sample data handling in Pathview. Data files are pre loaded and All the parameters have been preset.
    <br/>
    Data files used in this example are <a
            href="javascript:openNewWindow('http://10.3.201.55/reg/all/example/test/gse16873.d3.txt');">Gene
        Data</a> and <a href="javascript:openNewWindow('http://10.3.201.55/reg/all/example/test/sim.cpd.data2.csv');">Compound
        Data</a>.<br/>
    In this example gene data has 3 samples and compound data has 2 samples.<br/>
    <br/><u>Preset parameters(<i>KEGG View</i>):</u><br/>

    Pathway ID : <i>00640-Propanoate metabolism</i><br/>
    Species : <i>hsa</i><br/>
    Kegg Native : <i><b>TRUE</b></i><br/>
    Same Layer : <i>TRUE</i><br/>
    Multi State : <i>TRUE</i><br/>
    Match Data : <i>FALSE</i><br/>
    keys Align : <i>y</i><br/>
    <br/><a href="http://10.3.201.55/reg/example1a.php" target="_blank">Analysis Page</a>&nbsp;&nbsp;&nbsp;&nbsp;<a
            href="http://10.3.201.55/reg/example1a.html" target="_blank">Results Page</a><br/>

    <br/><u>Preset parameters(<i>Graphviz View</i>):</u><br/>

    Pathway ID : <i>00640-Propanoate metabolism</i><br/>
    Species : <i>hsa</i><br/>
    Kegg Native : <i><b>FALSE</b></i><br/>
    Same Layer : <i>TRUE</i><br/>
    Multi State : <i>TRUE</i><br/>
    Match Data : <i>FALSE</i><br/>
    keys Align : <i>y</i><br/>
    <br/><a href="http://10.3.201.55/reg/example1b.php" target="_blank">Analysis Page</a>&nbsp;&nbsp;&nbsp;&nbsp;<a
            href="http://10.3.201.55/reg/example1b.html" target="_blank">Results Page</a>




    <h3>2.ID Mapping</h3>
    Here's an example showing the ID mapping capability of pathview. Data files are pre loaded and All the parameters have been preset including Gene ID type and Compound ID type.
    <br/>
    Data files used in this example are <a
            href="javascript:openNewWindow('http://10.3.201.55/reg/all/example/test/gene.ensprot.txt');">Gene
        Data</a> and <a href="javascript:openNewWindow('http://10.3.201.55/reg/all/example/test/cpd.cas.csv');">Compound
        Data</a>.<br/>
    <br/><u>Preset parameters:</u><br/>
    Gene ID Type : <i>ENSEMBLPROT</i><br/>
    Compound ID Type : <i>CAS Registry Number</i><br/>
    Pathway ID : <i>00640-Propanoate metabolism</i><br/>
    Species : <i>hsa</i><br/>
    Kegg Native : <i>TRUE</i><br/>
    Same Layer : <i>TRUE</i><br/>
    keys Align : <i>y</i><br/>
    Limit : <br/>Gene:<i>3</i> Compound:<i>3</i><br/>
    Bins : <br/>Gene:<i>6</i> Compound:<i>6</i><br/>
    <br/><a href="http://10.3.201.55/reg/example2.php" target="_blank">Analysis Page</a>&nbsp;&nbsp;&nbsp;&nbsp;<a
            href="http://10.3.201.55/reg/example2.html" target="_blank">Results Page</a><p>


    <h2><a name="sec-2">Tutorial </a></h2>
    <a>The interface is divided into 3 main categories. Clicking on the category name shows the respective
        parameters.</a><br/></p>
    <ul>
        <li><a href="#input">Input/Output</a></li>
        <li><a href="#graphics">Graphics</a></li>
        <li><a href="#coloration">Coloration</a></li>
    </ul><br/>
    <img src="pics/1.png" alt="Categories"><br/><br/>
    <a name="input"><u><b>Input/Output:</b></u></a>
    <p>Uploading Data Files:</p>
    <p>Click Browse to upload gene data or compound data.</p>
    <img src="pics/2.png" alt="browse"><br/><br/>
    <p>Input file can be a R Data File(.rda), Text File(.txt) or a Comma Separated Value file(.csv). <b>Input Data
            should contain names of the variables at it's first line.</b></p><br/>


    <dl>
        <dt><a name="gidtype"><i>Gene ID Type</i></a></dt>
        <dd>ID type used for the Gene Data. This can be selected from the drop down. (Suggestions are shown on entering
            the initial letters)
        </dd>
        <br/>
        <dt><a name="cidtype"><i>Compound ID Type</i></a></dt>
        <dd>ID type used for the Compound Data. This can be selected from the drop down. (Suggestions are shown on
            entering the initial letters)
        </dd>
        <br/>
        <dt><a name="pathwayid1"><i>Pathway ID</i></a></dt>
        <dd>The KEGG pathway ID, usually 5 digit, may also include the 3 letter KEGG species code. (Suggestions are
            shown on entering the initial letters). Multiple Pathway IDs can be entered by clicking the “Add” button
        </dd>
        <br/>
        <dt><a name="species"><i>Species</i></a></dt>
        <dd>Either the KEGG code, scientific name or the common name of the target species. When KEGG ortholog pathway
            is considered, species is "ko". Default value for species is "hsa". (Suggestions are shown on entering the
            initial letters)
        </dd>
        <br/>
        <dt><a name="output"><i>Output Suffix</i></a></dt>
        <dd>The suffix to be added after the pathway name as part of the output graph file. Sample names or column names
            of the Gene Data or Compound Data are also added when there are multiple samples. Default value is
            "pathview".
        </dd>
        <br/>
    </dl>

    <hr>
    <br/>
    <a name="graphics"><u><b>Graphics:</b></u></a>
    <dl>
        <dt><a name="kegg"><i>Kegg Native</i></a></dt>
        <dd>Whether to render pathway graph as native KEGG graph (.png) or using graphviz layout engine (.pdf). Default
            is set to TRUE.
        </dd>
        <br/>
        <dt><a name="samelayer"><i>Same Layer</i></a></dt>
        <dd>Can either be TRUE or FALSE. Controls plotting layers: a) if node colors be plotted in the same layer as the
            pathway graph when kegg native is TRUE, 2) if edge/node type legend be
            plotted in the same page when kegg native is FALSE.
        </dd>
        <br/>
        <dt><a name="disc"><i>Discrete</i></a></dt>
        <dd>This argument tells whether gene data or compound data should be treated as discrete. Default values are
            both FALSE, i.e. both data should be treated as continuous.
        </dd>
        <br/>
        <dt><a name="kalign"><i>Keys Alignment</i></a></dt>
        <dd>Controls how the color keys are aligned when both Gene Data and Compound Data are not NULL. Potential values
            are "x", aligned by x coordinates, and "y", aligned by y coordinates. Default value is "x".
        </dd>
        <br/>
        <dt><a name="sgrp"><i>Split Group</i></a></dt>
        <dd>Whether split node groups are split to individual nodes. Each split member nodes inherits all edges from the
            node group. This option only affects graphviz graph view, i.e. when kegg native is FALSE. This option also
            effects most metabolic pathways even without group nodes defined originally. For these pathways, genes
            involved in the same reaction are grouped automatically when
            converting reactions to edges unless split group is TRUE. Default value is FALSE.
        </dd>
        <br/>
        <dt><a name="enode"><i>Expand Node</i></a></dt>
        <dd>Whether the multiple-gene nodes are expanded into single-gene nodes. Each expanded single-gene nodes
            inherits all edges from the original multiple gene node. This option only affects graphviz graph view, i.e.
            when kegg native is FALSE. This option is not effective for most metabolic pathways where it conflicts with
            converting reactions to edges. Default value is FALSE.
        </dd>
        <br/>
        <dt><a name="mstate"><i>Multi State</i></a></dt>
        <dd>Whether multiple states (samples or columns) gene data or compound data should be integrated and plotted in
            the same graph. Default match data is TRUE, In other words, gene or compound nodes will be sliced into
            multiple pieces corresponding to the number of states in the data.
        </dd>
        <br/>
        <dt><a name="mdata"><i>Match Data</i></a></dt>
        <dd>Whether the samples of gene data and Compound data are paired. Default match data is TRUE. When let sample
            sizes of gene data and compound data be m and n, when m>n, extra columns of NA’s (mapped to no color) will
            be added to Compound data as to make the sample size the same. This will result in the same number of slice
            in gene nodes and compound when multi state is TRUE.
        </dd>
        <br/>
        <dt><a name="spos"><i>Signature Position</i></a></dt>
        <dd>Controls the position of pathview signature. Default value is “bottomright”. Potential values can be found
            in the drop down list, or by typing in the initial letters.
        </dd>
        <br/>
        <dt><a name="kpos"><i>Key Position</i></a></dt>
        <dd>Controls the position of color key(s). Default value is “topleft”. Potential values can be found in the drop
            down list, or by typing in the initial letters.
        </dd>
        <br/>
        <dt><a name="offset"><i>Compound Label Offset</i></a></dt>
        <dd>Specifies how much compound labels should be put above the default position or node center. This is useful
            when compounds are labeled by full name, which affects the look of compound nodes
            and color. Only effective when kegg native is FALSE.
        </dd>
        <br/>

    </dl>
    <hr>
    <br/>
    <a name="coloration"><u><b>Coloration:</b></u></a>

    <dl>


        <dt><a name="nsum"><i>Node Sum</i></a></dt>
        <dd>The method name to calculate node summary given that multiple genes or compounds are mapped to it. Potential
            values can be found in the drop down list, or by typing in the initial letters. Default Value is “Sum”
        </dd>
        <br/>
        <dt><a name="ncol"><i>NA Color</i></a></dt>
        <dd>Color used for NA’s or missing values in Gene Data and Compound Data. Default value is “transparent”.</dd>
        <br/>
        <dt><a name="lmt"><i>Limit</i></a></dt>
        <dd>This argument specifies the limit values for Gene Data and Compound Data when converting them to pseudo
            colors.
            Input fields are enabled after checking respective checkpoints for Gene and Compound Data
        </dd>
        <br/>
        <dt><a name="bin"><i>Bins</i></a></dt>
        <dd>This argument
            specifies the number of levels or bins for Gene Data and Compound Data when converting them to pseudo
            colors. Default value is 10
        </dd>
        <br/>


        <dt><a name="lmh"><i>Low, Mid, High</i></a></dt>
        <dd>These arguments specify specifies the color spectra to code Gene Data and Compound Data.
            Default spectra (low-mid-high) "green"-"gray"-"red" and "blue"-
            "gray"-"yellow" are used for Gene Data and Compound Data respectively.
        </dd>
        <br/>
        <dt><a name="trans"><i>Transformation Function (Trans)</i></a></dt>
        <dd>This argument specifies whether and how gene.data and cpd.data are transformed. Examples are log, abs or
            users’ own functions.
        </dd>
        <br/>
    </dl>
    <p>On Submit, the results are shown on the window, they're also mailed to the user. User can go back from the
        results screen and modify any parameters by clicking the “Go Back and Edit” button.</p>

    <img src="pics/3.png" alt="back button" width="500" height="200"><br/><br/>
    <p>Clicking on any PNG files opens the KEGG View of that particular image.</p>

    <img src="pics/4.png" alt="kegg view" width="500" height="200"><br/><br/>
    <p>Clicking on any gene in KEGG View gives details of that particular gene.</p>

    <p>User can upload a new file for Gene or Compound data,the previously uploaded files are considered otherwise.
        Files names are shown at the top for reference.</p>
    <img src="pics/5.png" alt="cache" width="500" height="200">



@stop