<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/images/icon.png">

    <!-- Scripts -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}" type="text/css" media="screen"/>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    <script type="text/javascript" src="{{ asset('/js/sliding.form.js') }}"></script>

    <link href="{{ asset('/css/carousel.css') }}" rel="stylesheet">

    <link href="{{ asset('/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/style.css') }}" rel="stylesheet">
    {{--<link href="{{ asset('/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/dashboard.css') }}" rel="stylesheet">--}}
    <script>
        $("a").on("click", function () {

            alert("current section");
        });
    </script>
    <Style>
        p {
            text-align: justify;
        }
    </Style>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,600,700' rel='stylesheet'
          type='text/css'>

    <title>Pathview Tutorial and Training</title>
</head>
<body>
<nav class="navbar navbar-default">

    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}"><img src="/images/logo.png" height="40px"
                                                               style="margin-top: -17px">
                Pathway based data integration and visualization
            </a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true">Help
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="/tutorial">Attributes Help</a></li>
                        <li><a href="/tutorial">Custom Analysis</a></li>
                        <li><a href="/tutorial">Multiple sample KEGG view</a></li>
                        <li><a href="/tutorial">Multiple Sample Graphviz view</a></li>
                        <li><a href="/tutorial">ID mapping Analysis</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true">Pathview
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a target="_blank"
                               href="http://www.bioconductor.org/packages/release/bioc/html/pathview.html">Pathview
                                Bioconductor</a></li>
                        <li><a target="_blank"
                               href="http://www.bioconductor.org/packages/release/bioc/vignettes/pathview/inst/doc/pathview.pdf">Pathview
                                Bioconductor Tutorial</a></li>
                        <li><a target="_blank" href="http://pathview.r-forge.r-project.org/">Pathview R-Forge</a></li>
                    </ul>
                </li>
                <li><a href="/about">About</a></li>
                <li><a href="#">Related</a></li>

                @if (Auth::guest())
                    {{--<li><a href="{{ url('/auth/login') }}">Login</a></li>--}}
                    <li><a href="{{ url('/auth/login') }}">Login</a></li>
                    <li><a href="{{ url('/auth/register') }}">Register</a></li>
                    <li><a href="{{ url('/guest') }}"><img src="{{asset('/images/user.png')}}" alt="Login as Guest"
                                                           height="20px">Guest</a></li>

                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false"><img src="{{asset('/images/user.png')}}" alt="login user"
                                                      height="20px">{{ " ".Auth::user()->name }} <span
                                    class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/prev_anal/'.Auth::user()->id) }}">Previous Analysis</a></li>
                            <li><a href="{{ url('/user/'.Auth::user()->id) }}">Edit Profile</a></li>
                            <li><a href="{{ url('/auth/logout') }}">Logout</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<div class="col-md-12 content">

    <h1><b>Help Information</b></h1>

    <h1 class="arg_content">Custom Analysis</h1>

    <div class="row">
        <div class="col-lg-4">
            <a href="#Input"><img class="img-circle content" src="/images/file_upload.png" alt="Input & output"
                                      height="140" width="140"></a>
            <h2>Input & Output</h2>

            <p>Uploading Data Files for gene and compound data, selecting the species ID pathway ID,Gene id,Compound
                id,Suffix of your output </p>
            <br/>

            <div class="list-group">
                <ul class="nav navbar-nav" style="  margin-left: 30px;">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle list-group-item active" style="width:300px;"
                           data-toggle="dropdown">Options<span
                                    class="glyphicon glyphicon-download pull-right"></span></a>
                        <ul class="dropdown-menu" style="width:300px;">
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
                            <li style="width:300px;"><a href="#suffix">suffix</a></li>
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

            <p>Graphics deal with Location of elements in the graph and elements modification in the pathviw generation.
                For example number of bins in the pathview key position and signature position.</p>

            <div class="list-group">
                <ul class="nav navbar-nav" style="  margin-left: 30px;">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle list-group-item active" style="width:300px;"
                           data-toggle="dropdown">Options<span
                                    class="glyphicon glyphicon-download pull-right"></span></a>
                        <ul class="dropdown-menu" style="width:300px;">
                            <li style="width:300px;"><a href="#kegg">Kegg Native</a></li>
                            <li class="divider"></li>
                            <li style="width:300px;"><a href="#layer">Same Layer</a></li>
                            <li class="divider"></li>
                            <li style="width:300px;"><a href="#desc">Descrete</a></li>
                            <li class="divider"></li>
                            <li style="width:300px;"><a href="#split">Split</a></li>
                            <li class="divider"></li>
                            <li style="width:300px;"><a href="#expand">Expand</a></li>
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

            <p>Colouration deal with various states of the gene using colors you can enter the colors gene should be
                when gene is on high state, mid state and low state for both gene and compound data</p>

            <div class="list-group">
                <ul class="nav navbar-nav" style="  margin-left: 30px;">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle list-group-item active" style="width:300px;"
                           data-toggle="dropdown">Options<span
                                    class="glyphicon glyphicon-download pull-right"></span></a>
                        <ul class="dropdown-menu" style="width:300px;">
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
        <!-- /.col-lg-4 -->
    </div>
    <div class="col-sm-12">
        <h1 class="arg_content">Example Analysis</h1>


        <div class="col-md-4">
            <h2>Multiple sample KEGG view</h2>

            <p>This example shows the capability of multiple sample data handling in Pathview.</p>

            <p><a class="btn btn-default" href="#example1" role="button">View details »</a></p>
        </div>
        <div class="col-md-5">
            <h2>Multiple Sample Graphviz view</h2>

            <p>This example shows the capability of multiple sample data handling in Pathview.with GRAPHVIZ output
                generation</p>

            <p><a class="btn btn-default" href="#example2" role="button">View details »</a></p>
        </div>
        <div class="col-md-3">
            <h2>ID Mapping</h2>

            <p>Here's an example showing the ID mapping capability of pathview.</p>

            <p><a class="btn btn-default" href="#example3" role="button">View details »</a></p>
        </div>
    </div>
    <div class="col-sm-12">
        <section id="Input">
            <h1 class="arg_content">Input and Output</h1>

            {{--
                  <div class="page-header col-sm-6">
        <div id="myCarousel" class=" carousel slide col col-sm-12 " data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                <li class="" data-target="#myCarousel" data-slide-to="1"></li>
                <li class="" data-target="#myCarousel" data-slide-to="2"></li>
                <li class="" data-target="#myCarousel" data-slide-to="3"></li>
                <li class="" data-target="#myCarousel" data-slide-to="4"></li>
                <li class="" data-target="#myCarousel" data-slide-to="5"></li>
                <li class="" data-target="#myCarousel" data-slide-to="6"></li>

            </ol>
            <div class="carousel-inner" role="listbox">
            <div class="item active">
                    <img class="first-slide" src="/images/New Analyses/input&output/newanalyses-input&output.PNG" alt="First slide">
                    --}}{{--<div class="container">
                        <div class="carousel-caption">
                            <h1>Slide 1.</h1>
                            <p>hello.</p>
                            <p><a class="btn btn-lg btn-primary" href="#" role="button">Sign up today</a></p>
                        </div>
                    </div>--}}{{--
                </div>
                <div class="item">
                    <img class="second-slide" src="/images/New Analyses/input&output/newanalyses-fileupload.png" alt="First slide">
                    --}}{{--<div class="container">
                        <div class="carousel-caption">
                            <h1>Slide 2.</h1>
                            <p>hello world.</p>
                            <p><a class="btn btn-lg btn-primary" href="#" role="button">Learn more</a></p>
                        </div>
                    </div>--}}{{--
                </div>
                <div class="item">
                    <img class="third-slide" src="/images/New Analyses/input&output/newanalyses-geneid.png" alt="First slide">
                    --}}{{--<div class="container">
                        <div class="carousel-caption">
                            <h1>Slide 3.</h1>
                            <p>bye.</p>
                            <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
                        </div>
                    </div>--}}{{--
                </div>
                <div class="item">
                    <img class="third-slide" src="/images/New Analyses/input&output/newanalyses-compoundid.png" alt="First slide">
                    --}}{{--<div class="container">
                        <div class="carousel-caption">
                            <h1>Slide 4.</h1>
                            <p>bye.</p>
                            <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
                        </div>
                    </div>--}}{{--
                </div>
                <div class="item">
                    <img class="third-slide" src="/images/New Analyses/input&output/newanalyses-species.png" alt="First slide">
                    --}}{{--<div class="container">
                        <div class="carousel-caption">
                            <h1>Slide 5.</h1>
                            <p>bye.</p>
                            <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
                        </div>
                    </div>--}}{{--
                </div>
                <div class="item">
                    <img class="third-slide" src="/images/New Analyses/input&output/newanalyses-pathway.png" alt="First slide">
                    --}}{{--<div class="container">
                        <div class="carousel-caption">
                            <h1>Slide 5.</h1>
                            <p>bye.</p>
                            <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
                        </div>
                    </div>--}}{{--
                </div>
                <div class="item">
                    <img class="third-slide" src="/images/New Analyses/input&output/newanalyses-suffix.png" alt="First slide">
                    --}}{{--<div class="container">
                        <div class="carousel-caption">
                            <h1>Slide 5.</h1>
                            <p>bye.</p>
                            <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
                        </div>
                    </div>--}}{{--
                </div>
                <a  href="#myCarousel" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a  href="#myCarousel" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>

        </div>

    </div>--}}

            {{-- <img  src="/images/input.png"  height="583" width="600">--}}


            <div class="page-header col-sm-12">
                <dl>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="gene_data">Gene Data</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>Gene data typically accepts CSV, Rdata, txt file</dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="cpd_data">Compound Data</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>Compound gene data accepts CSV, Rdata, txt file</dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="gene_id">Gene ID Type</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>ID type used for the Gene Data. This can be selected from the drop down. (Suggestions
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
                            <dd>The KEGG pathway ID, usually 5 digit, may also include the 3 letter KEGG species code.
                                (Suggestions are shown on entering the initial letters). Multiple Pathway IDs can be
                                entered by clicking the “Add” button
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
                            <dd>The suffix to be added after the pathway name as part of the output graph file. Sample
                                names or column names of the Gene Data or Compound Data are also added when there are
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
            {{--<div class="page-header col-sm-6">
            <div class="page-header">
                <div class="marketing-hero-inner">
                    <div id="myCarousel" class=" carousel slide col col-sm-12 " data-ride="carousel">
                        <!-- Indicators -->

                        <ol class="carousel-indicators">
                            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                            <li class="" data-target="#myCarousel" data-slide-to="1"></li>
                            <li class="" data-target="#myCarousel" data-slide-to="2"></li>
                            <li class="" data-target="#myCarousel" data-slide-to="3"></li>
                            <li class="" data-target="#myCarousel" data-slide-to="4"></li>
                            <li class="" data-target="#myCarousel" data-slide-to="5"></li>
                            <li class="" data-target="#myCarousel" data-slide-to="6"></li>
                            <li class="" data-target="#myCarousel" data-slide-to="7"></li>

                        </ol>
                        <div class="carousel-inner" role="listbox">
                            <div class="item active">
                                <img class="first-slide" src="/images/New Analyses/graphics/newanalyses-graphics.png" alt="First slide">
                                --}}{{--<div class="container">
                                    <div class="carousel-caption">
                                        <h1>Slide 1.</h1>
                                        <p>hello.</p>
                                        <p><a class="btn btn-lg btn-primary" href="#" role="button">Sign up today</a></p>
                                    </div>
                                </div>--}}{{--
                            </div>
                            <div class="item">
                                <img class="second-slide" src="/images/New Analyses/graphics/newanalyses-graphics-kegg.png" alt="First slide">
                                --}}{{--<div class="container">
                                    <div class="carousel-caption">
                                        <h1>Slide 2.</h1>
                                        <p>hello world.</p>
                                        <p><a class="btn btn-lg btn-primary" href="#" role="button">Learn more</a></p>
                                    </div>
                                </div>--}}{{--
                            </div>
                            <div class="item">
                                <img class="third-slide" src="/images/New Analyses/graphics/newanalyses-graphics-discrete.png" alt="First slide">
                                --}}{{--<div class="container">
                                    <div class="carousel-caption">
                                        <h1>Slide 3.</h1>
                                        <p>bye.</p>
                                        <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
                                    </div>
                                </div>--}}{{--
                            </div>
                            <div class="item">
                                <img class="third-slide" src="/images/New Analyses/graphics/newanalyses-graphics-split&expand.png" alt="First slide">
                                --}}{{--<div class="container">
                                    <div class="carousel-caption">
                                        <h1>Slide 4.</h1>
                                        <p>bye.</p>
                                        <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
                                    </div>
                                </div>--}}{{--
                            </div>
                            <div class="item">
                                <img class="third-slide" src="/images/New Analyses/graphics/newanalyses-graphics-multistate&matchdata.png" alt="First slide">
                                --}}{{--<div class="container">
                                    <div class="carousel-caption">
                                        <h1>Slide 5.</h1>
                                        <p>bye.</p>
                                        <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
                                    </div>
                                </div>--}}{{--
                            </div>
                            <div class="item">
                                <img class="third-slide" src="/images/New Analyses/graphics/newanalyses-graphics-compoundlabeloffset.png" alt="First slide">
                                --}}{{--<div class="container">
                                    <div class="carousel-caption">
                                        <h1>Slide 5.</h1>
                                        <p>bye.</p>
                                        <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
                                    </div>
                                </div>--}}{{--
                            </div>
                            <div class="item">
                                <img class="third-slide" src="/images/New Analyses/graphics/newanalyses-graphics-keyalignment.png" alt="First slide">
                                --}}{{--<div class="container">
                                    <div class="carousel-caption">
                                        <h1>Slide 5.</h1>
                                        <p>bye.</p>
                                        <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
                                    </div>
                                </div>--}}{{--
                            </div>
                            <div class="item">
                                <img class="third-slide" src="/images/New Analyses/graphics/newanalyses-graphics-signature-position.png" alt="First slide">
                                --}}{{--<div class="container">
                                    <div class="carousel-caption">
                                        <h1>Slide 5.</h1>
                                        <p>bye.</p>
                                        <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
                                    </div>
                                </div>--}}{{--
                            </div>
                            <a  href="#myCarousel" role="button" data-slide="next">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a  href="#myCarousel" role="button" data-slide="next">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>

                    </div>

                </div>
                </div>
                </div>
--}}
            {{-- <img  src="/images/graphics_input.png"  height="600" width="600">--}}


            <div class="page-header col-sm-12">
                <dl>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="kegg">Kegg Native</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd> Whether to render pathway graph as native KEGG graph (.png) or using graphviz layout
                                engine (.pdf). Default is set to TRUE.
                            </dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="layer">Same Layer</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd> Can either be TRUE or FALSE. Controls plotting layers: a) if node colors be plotted in
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
                            <dd>This argument tells whether gene data or compound data should be treated as discrete.
                                Default values are both FALSE, i.e. both data should be treated as continuous.
                            </dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="kalign">Keys Alignment</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>Controls how the color keys are aligned when both Gene Data and Compound Data are not
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
                                inherits all edges from the node group. This option only affects graphviz graph view,
                                i.e. when kegg native is FALSE. This option also effects most metabolic pathways even
                                without group nodes defined originally. For these pathways, genes involved in the same
                                reaction are grouped automatically when converting reactions to edges unless split group
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
                                single-gene nodes inherits all edges from the original multiple gene node. This option
                                only affects graphviz graph view, i.e. when kegg native is FALSE. This option is not
                                effective for most metabolic pathways where it conflicts with converting reactions to
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
                                integrated and plotted in the same graph. Default match data is TRUE, In other words,
                                gene or compound nodes will be sliced into multiple pieces corresponding to the number
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
                                TRUE. When let sample sizes of gene data and compound data be m and n, when m>n, extra
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
                            <dd>Controls the position of pathview signature. Default value is “bottomright”. Potential
                                values can be found in the drop down list, or by typing in the initial letters.
                            </dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="kpos">Key Position</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>Controls the position of color key(s). Default value is “topleft”. Potential values can
                                be found in the drop down list, or by typing in the initial letters.
                            </dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="clabel">Compound Label Offset</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>Specifies how much compound labels should be put above the default position or node
                                center. This is useful when compounds are labeled by full name, which affects the look
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
            {{--  <!--<div class="page-header col-sm-6 ">
                  <div id="myCarousel3" class=" carousel slide col col-sm-12 " data-ride="carousel">
                      <!-- Indicators -->
                      <ol class="carousel-indicators">
                          <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                          <li class="" data-target="#myCarousel3" data-slide-to="1"></li>
                          <li class="" data-target="#myCarousel3" data-slide-to="2"></li>
                          <li class="" data-target="#myCarousel3" data-slide-to="3"></li>
                          <li class="" data-target="#myCarousel3" data-slide-to="4"></li>
                          <li class="" data-target="#myCarousel3" data-slide-to="5"></li>
                          <li class="" data-target="#myCarousel3" data-slide-to="6"></li>
                          <li class="" data-target="#myCarousel3" data-slide-to="7"></li>

                      </ol>
                      <div class="carousel-inner" role="listbox">
                          <div class="item active">
                              <img class="first-slide" src="/images/New Analyses/coloration/newanalyses-coloration.PNG" alt="First slide">
                              --}}{{--<div class="container">
                                  <div class="carousel-caption">
                                      <h1>Slide 1.</h1>
                                      <p>hello.</p>
                                      <p><a class="btn btn-lg btn-primary" href="#" role="button">Sign up today</a></p>
                                  </div>
                              </div>--}}{{--
                          </div>
                          <div class="item">
                              <img class="second-slide" src="/images/New Analyses/coloration/newanalyses-coloration-NodeSum.png" alt="First slide">
                              --}}{{--<div class="container">
                                  <div class="carousel-caption">
                                      <h1>Slide 2.</h1>
                                      <p>hello world.</p>
                                      <p><a class="btn btn-lg btn-primary" href="#" role="button">Learn more</a></p>
                                  </div>
                              </div>--}}{{--
                          </div>
                          <div class="item">
                              <img class="third-slide" src="/images/New Analyses/coloration/newanalyses-coloration-Nacolor.png" alt="First slide">
                              --}}{{--<div class="container">
                                  <div class="carousel-caption">
                                      <h1>Slide 3.</h1>
                                      <p>bye.</p>
                                      <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
                                  </div>
                              </div>--}}{{--
                          </div>
                          <div class="item">
                              <img class="third-slide" src="/images/New Analyses/coloration/newanalyses-Gene Limit.png" alt="First slide">
                              --}}{{--<div class="container">
                                  <div class="carousel-caption">
                                      <h1>Slide 4.</h1>
                                      <p>bye.</p>
                                      <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
                                  </div>
                              </div>--}}{{--
                          </div>
                          <div class="item">
                              <img class="third-slide" src="/images/New Analyses/coloration/newanalyses-bins.png" alt="First slide">
                              --}}{{--<div class="container">
                                  <div class="carousel-caption">
                                      <h1>Slide 5.</h1>
                                      <p>bye.</p>
                                      <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
                                  </div>
                              </div>--}}{{--
                          </div>
                          <div class="item">
                              <img class="third-slide" src="/images/New Analyses/coloration/newanalyses-low.png" alt="First slide">
                              --}}{{--<div class="container">
                                  <div class="carousel-caption">
                                      <h1>Slide 5.</h1>
                                      <p>bye.</p>
                                      <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
                                  </div>
                              </div>--}}{{--
                          </div>
                          <div class="item">
                              <img class="third-slide" src="/images/New Analyses/coloration/newanalyses-mid.png" alt="First slide">
                              --}}{{--<div class="container">
                                  <div class="carousel-caption">
                                      <h1>Slide 5.</h1>
                                      <p>bye.</p>
                                      <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
                                  </div>
                              </div>--}}{{--
                          </div>
                          <div class="item">
                              <img class="third-slide" src="/images/New Analyses/coloration/newanalyses-high.png" alt="First slide">
                              --}}{{--<div class="container">
                                  <div class="carousel-caption">
                                      <h1>Slide 5.</h1>
                                      <p>bye.</p>
                                      <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
                                  </div>
                              </div>--}}{{--
                          </div>
                          <a  href="#myCarousel" role="button" data-slide="next">
                              <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                              <span class="sr-only">Previous</span>
                          </a>
                          <a  href="#myCarousel" role="button" data-slide="next">
                              <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                              <span class="sr-only">Next</span>
                          </a>
                      </div>

                  </div>
                   </div>-->--}}



            {{--<img  src="/images/coloration_input.png"  height="600" width="600">--}}

            <div class="page-header col-sm-12">
                <dl>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="nsum">Node Sum</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>The method name to calculate node summary given that multiple genes or compounds are
                                mapped to it. Potential values can be found in the drop down list, or by typing in the
                                initial letters. Default Value is “Sum”
                            </dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="nclolor">NA Color</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>Color used for NA’s or missing values in Gene Data and Compound Data. Default value is
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
                                converting them to pseudo colors. Input fields are enabled after checking respective
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
                            <dd>These arguments specify specifies the color spectra to code Gene Data and Compound Data.
                                Default spectra (low-mid-high) "green"-"gray"-"red" and "blue"- "gray"-"yellow" are used
                                for Gene Data and Compound Data respectively.
                            </dd>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading argument">
                            <dt><a name="trans">Transformation Function (Trans)</a></dt>
                        </div>
                        <div class="panel-body argument">
                            <dd>This argument specifies whether and how gene.data and cpd.data are transformed. Examples
                                are log, abs or users’ own functions.
                            </dd>
                        </div>
                    </div>
                </dl>
            </div>
        </section>


    </div>
    <div class="col-sm-12">

        <section id="example1">
            <div class="arg_content">

                <h1 class="arg_content">Example 1: Multiple sample KEGG view</h1></div>
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
                In this example gene data has 3 samples and compound data has 2 samples. Difference on this example is
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
            <div class="arg_content">
                <h1 class="arg_content">Example 3: ID Mapping</h1></div>
            <p>
                Here's an example showing the ID mapping capability of pathview. Data files are pre loaded and All the
                parameters have been preset including Gene ID type and Compound ID type.
                Data files used in this example are <br/><a href="all/demo/example/gene.ensprot.txt" target="_blank">Gene
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
                    <li class="list-group-item">Pathway ID : 00640-Propanoate metabolism</li>
                    <li class="list-group-item">Species : hsa</li>
                    <li class="list-group-item">Gene Id: ENSEMBLPROT</li>
                    <li class="list-group-item">Compound Id: CAS Registry Number</li>
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

@include('footer')
</body>
</html>