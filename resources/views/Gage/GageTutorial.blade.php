@extends('GageApp')

@section('content')


    <div class="col-md-12 content" style="text-align: center;">

        <h1><b>Help Information</b></h1>



        <div class="col-md-12 content">
            <h1 class="arg_content">Custom Analysis</h1>
            <div class="col-lg-6">
                <a href="#Input"><img class="img-circle content" src="/images/file_upload.png" alt="Input & output"
                                      height="250" width="250"></a>

                <h2>Input & Output</h2>

                <p></p>


            </div>


            <div class="col-lg-6">
                <a href="#analysis"><img class="img-circle" src="/images/analysis.png" alt="Analysis" height="250"
                                           width="250"></a>

                <h2>Analysis</h2>

                <p></p>



            </div>

        </div>
        <div class="col-md-12 content" >
            <div class="col-md-6">
                <div class="list-group">

                    <ul class="nav navbar-nav" style="width:100%">
                        <li class="dropdown" style="width:100%;margin-bottom: 10px;">
                            <a href="#" class="dropdown-toggle list-group-item active"
                               data-toggle="dropdown">Options<span
                                        class="glyphicon glyphicon-download pull-right" ></span></a>
                            <ul class="dropdown-menu" style="width:100%">

                                <li style="width:300px;"><a href="#assay_data">Assay Data</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#gene_set">Gene Set</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#gene_id_type">Gene ID Type</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#species">Species</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#contorl_reference">Control/Reference</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#case_sample">Case/Sample</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="list-group">

                    <ul class="nav navbar-nav" style="width:100%">
                        <li class="dropdown" style="width:100%;margin-bottom: 10px;">
                            <a href="#" class="dropdown-toggle list-group-item active"
                               data-toggle="dropdown">Options<span
                                        class="glyphicon glyphicon-download pull-right" ></span></a>
                            <ul class="dropdown-menu" style="width:100%">

                                <li style="width:300px;"><a href="#q_value_cutoff">q-value Cutoff</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#set_size">Set Size</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#compare">Compare</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#two_direction_test">Two-direction Test</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#srank_test">Rank Test</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#pre_gene_score">Per Gene Score</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#gene_set_test">Gene Set Test</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#use_pathview">Use Pathview</a></li>
                                <li class="divider"></li>
                                <li style="width:300px;"><a href="#data_type">Data Type</a></li>
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
        <div class="col-md-12 content">

            <h1 class="arg_content">Example Analysis</h1>
            <div class="col-md-4">
                <div style="height:70px"><h2>GAGE Example 1</h2></div>

                <p></p>

                <p><a class="btn btn-default" href="#example1" role="button">View details »</a></p>
            </div>
            <div class="col-md-4">
                <div style="height:70px"><h2>GAGE Example 2</h2></div>

                <p></p>

                <p><a class="btn btn-default" href="#example2" role="button">View details »</a></p>
            </div>
            <div class="col-md-4">
                <div style="height:70px"> <h2>GAGE Example 3</h2></div>

                <p></p>

                <p><a class="btn btn-default" href="#example3" role="button">View details »</a></p>
            </div>
        </div>
        <div class="col-md-12 content">

            <h1 class="arg_content">Input and Output</h1>

                <dl>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="assay_data">Assay Data</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd> Input data containing an expression matrix or matrix-like data structure, with genes as rows and samples as columns. Accepts only CSV and TXT as extension.</dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="gene_set">Gene Set</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>Gene set can be taken from 3 categories kegg, Go and Custom gene set uploaded using a txt or csv file containing the name list each element contains a gene set that is a character vector of gene IDs or symbols
                            <ul style="list-style-type: none;">
                                <h3>Kegg</h3>
                                <li><b>Sinaling</b> : sig.idx </li>
                                <li><b>Metabolic</b> : met.idx</li>
                                <li><b>Sigmet</b> : sigmet.idx</li>
                                <li><b>disease</b> : dise.idx</li>
                                <li><b>all</b> :  c(sigmet.idx,dise.idx)</li>
                                <h3>GO</h3>
                                <li><b>BP</b> : GSETS.BP </li>
                                <li><b>CC</b> : GSETS.CC</li>
                                <li><b>MF</b> : GSETS.MF</li>
                                <li><b>all</b> : BP+CC+MF</li>
                            </ul>
                                If Gene set is chosen as custom a file upload is shown user have to upload the file for the custom gene set 'Species' value is set to custom and 'Gene Id Type' is also set to custom
                            </dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="gene_id_type">Gene ID Type</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>
                                ID type used for the Gene Data. This can be selected from the drop down list.
                                for GO Gene sets the list is restricted to the Gene ID paired with species and auto changes with species value
                                If you are giving the custom gene set Id's then the values is set to custom.
                            </dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="species">Species</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>Either the KEGG code, scientific name or the common name of the target species.This can be selected from the autosuggest drop down list.
                            </dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="contorl_reference">Control/Reference</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>
                                Column numbers for the reference condition or phenotype i.e. control group
                                if you specify null than all the columns are considered as target experiments.
                            </dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="case_sample">Case / Sample</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>
                                Column numbers for the target condition or phenotype i.e. experiment group in the exprs data matrix.
                                if you specify null than all the columns other than ref are considered as target experiments
                            </dd>
                        </div>
                    </div>
                </dl>

            </div>
        <div class="col-md-12 content">

            <h1 class="arg_content">Analysis</h1>

                <dl>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="q_value_cutoff">q-value Cutoff</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>
                                numeric, q-value cutoff between 0 and 1 for signficant gene sets selection.  De-
                                fault to be 0.1.
                            </dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="set_size">Set Size</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>
                                Gene set size (number of genes) range to be considered for enrichment test. Tests
                                for too small or too big gene sets are not robust statistically or informative bio-
                                logically.
                            </dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="compare">Compare</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>
                                Comparison scheme to be used. List of comparison methods available
                                <ul style="list-style-type: none;">
                                    <li> <b>paired</b>: default reference and sample columns should be of equal number of columns</li>
                                    <li> <b>unpaired</b>: one on one comparison between reference and sample columns </li>
                                    <li> <b>1 on Group</b>: one sample column at a time vs the average of the refernce columns </li>
                                    <li> <b>as group</b>: Used for PAGE-like analysis </li>
                                </ul>
                            </dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="two_direction_test">Two-direction Test</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>
                                To test for changes changes towards both directions simultaneously.
                                or test a gene set toward a single direction(all genes up or down regulated)
                            </dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="rank_test">Rank Test</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>
                                whether do the optional rank based two-sample t-test (equiv-
                                alent to the non-parametric Wilcoxon Mann-Whitney test) instead of parametric
                                two-sample t-test. This argument should be used
                                with respect to argument Test
                            </dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="per_gene_score">Per Gene Score</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>Whether to use fold changes or t-test statistics as per gene statistics.
                            </dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="gene_set_test">Gene Set Test</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>
                                Function used for gene set tests for single array based analysis. Default to be
                                t-Test, which features a two-sample t-test for differential expression of gene
                                sets.
                                Other options includes:
                                <p>
                                <ul style="list-style-type: none;">
                                    <li><b>z-Test</b>: using one-sample z-test as in PAGE</li>
                                    <li><b>Kolmogorov–Smirnov test</b>: using the non-parametric Kolmogorov-Smirnov tests as in GSEA</b></li>
                                </ul>
                                </p>
                                gs.zTest or Kolmogorov–Smirnov test should only be used when rank.test is set to false
                            </dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="use_pathview">Use Pathview</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>
                                Argument to perform pathview generation or not.
                            </dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="data_type">Data Type</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>
                                Data type Gene,Compound while generating the pathviews.
                                This argument is visible only when use pathview option is made to true.
                            </dd>
                        </div>
                    </div>
                </dl>

        </div>
        <div class="col-md-12 content">

            <h1 class="arg_content">Example 1: GAGE Analysis</h1>




               <div class="col-sm-6">
                <p>Output Plots and heatmap snapshots:</p>
                        <img class="gageExampleImg" src="images/gage/gage.res.gs.heatmap-page-003.jpg" >
                   <h3><b><u>Genet Set Combined Heat map</u></b></h3>
                    </div>
                    <div class="col-sm-6">
                        <p> Arguments used in example analysis:</p>
                    <ul class="list-group">
                        <li class="list-group-item disabled">
                            Applied Options
                        </li>
                        <li class="list-group-item">reference : 1,3</li>
                        <li class="list-group-item">sample : 2,4</li>
                        <li class="list-group-item">filename : gagedata.txt</li>
                        <li class="list-group-item">geneSetCategory : kegg</li>
                        <li class="list-group-item">geneSet : sig.idx</li>
                        <li class="list-group-item">species : hsa</li>
                        <li class="list-group-item">cutoff : 0.1</li>
                        <li class="list-group-item">geneIdType : entrez</li>
                        <li class="list-group-item">setSizeMin : 10</li>
                        <li class="list-group-item">setSizeMax : INF</li>
                        <li class="list-group-item">compare : paired</li>
                        <li class="list-group-item">test.2d : False</li>
                        <li class="list-group-item">rankTest : False</li>
                        <li class="list-group-item">useFold : True</li>
                        <li class="list-group-item">test : gs.tTest</li>
                        <li class="list-group-item">do.pathview : F</li>
                    </ul>
                    <a href="/gageExample1">
                        <button type="button" class="btn btn-primary btn-lg GetStarted" data-toggle="modal">
                            Try It
                        </button>
                    </a>
                        </div>
                <div class="col-sm-6">

                    <img class="gageExampleImg" src="images/gage/hsa03060.geneData.heatmap-page-001.jpg" >
                    <h3><b><u>Individual Heat Map</u></b></h3>
                </div>
                <div class="col-sm-6">

                    <img class="gageExampleImg" src="images/gage/hsa03060.geneData-page-001.jpg" >
                    <h3><b><u>Individual Scatter Plot</u></b></h3>
                </div>


        </div>
    </div>
    @stop