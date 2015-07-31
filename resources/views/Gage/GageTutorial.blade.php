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
            <div class="page-header col-sm-12">
                <dl>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="assay_data">Assay Data</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd> </dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="gene_set">Gene Set</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd></dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="gene_id_type">Gene ID Type</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd></dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="species">Species</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>
                            </dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="contorl_reference">Control/Reference</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>
                            </dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="case_sample">Case / Sample</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>
                            </dd>
                        </div>
                    </div>
                </dl>
            </div>
            </div>
        <div class="col-md-12 content">

            <h1 class="arg_content">Analysis</h1>
            <div class="page-header col-sm-12">
                <dl>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="q_value_cutoff">q-value Cutoff</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd></dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="set_size">Set Size</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd></dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="compare">Compare</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd></dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="two_direction_test">Two-direction Test</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd></dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="rank_test">Rank Test</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>
                            </dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="per_gene_score">Per Gene Score</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>
                            </dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="gene_set_test">Gene Set Test</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>
                            </dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="use_pathview">Use Pathview</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>
                            </dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="data_type">Data Type</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>
                            </dd>
                        </div>
                    </div>
                </dl>
            </div>
        </div>
        <div class="col-md-12 content">

            <h1 class="arg_content">Example 1: GAGE Analysis</h1>
        </div>
    </div>
    @stop