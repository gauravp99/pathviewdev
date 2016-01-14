@extends('GageApp')

@section('content')

    <div class="content">
        <div class="rows">
            <div class="col-sm-12">
                <div class="col-sm-8 corousel-content ">
                    <h2 class="marketing-hero-heading">
                        <a href="http://www.bioconductor.org/packages/release/bioc/html/gage.html" target="_blank">GAGE</a> is an established method for gene set (enrichment or GSEA) or pathway analysis. GAGE is generally applicable to different omics data with robust performance.
                    </h2>
                </div>
                <div class="col-sm-4">
                    <a href="/gage">
                        <button type="button"  class="btn btn-primary btn-lg GetStarted" style="margin-top:20px;" >
                            Quick Start
                        </button>
                    </a>
                </div>
                </div>
            <div class="col-sm-12">
                <div class="col-sm-8 corousel-content ">
                    <h2 class="marketing-hero-heading">
                        GAGE Web provides easy interactive access to GAGE analysis. It features:</h2>


                        <ol style="margin-left:30px;">
                            <li>A complete pathway analysis workflow based on GAGE and Pathview</li>
                            <li>Support to >3000 species, dozens of molecular IDs, various omics data and gene-set data (KEGG pathways, Gene Ontology, SMPDB etc);</li>
                            <li>Over-representation test on preselected gene or molecule lists.</li>
                        </ol>

                </div>
                <div class="col-sm-4">
                    <a href="/analysis">
                        <button type="button"  class="btn btn-primary btn-lg GetStarted" style="margin-top:50px;">
                            Try Pathview
                        </button>
                    </a>
                </div>
            </div>


            <div class="col-sm-12">
                <div class="col-sm-8 corousel-content">
                    <div class="marketing-hero-inner" style="margin-top:20px;">
                        <div id="myCarousel" class=" carousel slide col col-sm-12 " data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                                <li class="" data-target="#myCarousel" data-slide-to="1"></li>
                                <li class="" data-target="#myCarousel" data-slide-to="2"></li>
                                <li class="" data-target="#myCarousel" data-slide-to="3"></li>
                                <li class="" data-target="#myCarousel" data-slide-to="4"></li>
                            </ol>
                            <div class="carousel-inner" role="listbox">
                                <div class="item active">
                                    <img class="first-slide" src="../images/gfirstslide.png" alt="First slide">
                                </div>
                                <div class="item">
                                    <img class="second-slide" src="../images/gsecondslide.png" alt="Second slide">
                                </div>
                                <div class="item">
                                    <img class="third-slide" src="../images/gthirdslide.png" alt="Third slide">
                                </div>
                                <div class="item">
                                    <img class="third-slide" src="../images/gfourthslide.png" alt="Fourth slide">
                                </div>
                                <div class="item">
                                    <img class="third-slide" src="../images/gfifthslide.png" alt="Fifth slide">
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-sm-12 ">
                        <h2 class="homepage-heading">Citations:</h2>

                        <p class="content1"><i>
                                Luo W, Friedman M, etc. GAGE: generally applicable gene set enrichment for pathway analysis. BMC Bioinformatics, 2009, 10, pp. 161, doi: 10.1186/1471-2105-10-161
                                <br/>
                                Luo W, Brouwer C. Pathview: an R/Biocondutor package for pathway-based data integration and visualization. Bioinformatics, 2013, 29(14):1830-1831, doi: 10.1093/bioinformatics/btt285
                            </i></p>

                        <p class="content1">
                        </p>
                        <br>

                        <p class="content1">
                            Please cite the GAGE paper and pathview.uncc.edu if you use this website. In addition, please cite the Pathview paper if you the Pathview visualization here.
                            This will help the GAGE and Pathview projects in return.
                        </p>
                    </div>

                    <div class="col-sm-12 contactDiv">
                        <h2 class="homepage-heading">Contact:</h2>

                        <p class="contact">Email us: <a href="mailto:pathomics@gmail.com">pathomics@gmail.com
                            </a></p>

                    </div>

                </div>
                <div class="col-sm-4 leftsidebar">
                    <div class="panel panel-default">
                        <div class="panel-heading leftpanel " >Login</div>
                        <div class="panel-body">


                            <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">E-Mail</label>
                                    <div class="col-md-6">
                                        <input type="email" class="form-control" name="email"
                                               value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Password</label>
                                    <div class="col-md-6">
                                        <input type="password" class="form-control" name="password">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="remember"> Remember Me
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary welcomeLogin">
                                            Login
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <a class="btn btn-link" href="{{ url('/password/email') }}">
                                            Forgot Your Password?
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <button type="button" class="btn btn-primary btn-lg register clickRegister" data-toggle="modal" data-target="#myModal">
                            Click here to Register
                        </button>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading leftpanel" >Usage Statistics</div>
                        <div class="panel-body">
                            <p >GAGE web</p>

                            <div id="graph1" class="col-md-12">


                                <table class="tables" >
                                    <tr>

                                        <td>
                                            <div class="bar12">
                                                &nbsp
                                            </div>
                                        </td>
                                        <td class="tdContent">GAGE Analysis:</td>
                                        <td><?php echo $web_dnld_cnt ?></td>
                                        <td>
                                            <div class="bar11" >
                                                &nbsp
                                            </div>
                                        </td>
                                        <td  class="tdContent">IP's:</td>
                                        <td><?php echo $web_ip_cnt ?></td>
                                    </tr>

                                </table>
                            </div>
                            <canvas id="myChart" ></canvas>
                            <p> Bioc Package</p>
                            <div id="graph1" class="col-md-12">
                                <table class="tables">
                                    <tr>
                                        <td>
                                            <div class="bar2">
                                                &nbsp&nbsp
                                            </div>
                                        </td>

                                        <td  class="tdContent">Downloads:</td>
                                        <td>&#8776;<?php echo $bioc_dnld_cnt ?></td>


                                        <td>
                                            <div class="bar1" >
                                                &nbsp&nbsp
                                            </div>
                                        </td>

                                        <td  class="tdContent">IP's:</td>
                                        <td>&#8776;<?php echo $bioc_ip_cnt ?></td>

                                    </tr>
                                </table>
                            </div>
                            <canvas id="myChart1" ></canvas>
                        </div>

                    </div>
                </div>



            </div>
        </div>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title " id="myModalLabel">Register</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form" method="POST"
                          action="{{ url('/auth/register') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group">
                            <label class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name"
                                       value="{{ old('name') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Organisation</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="organisation"
                                       value="{{ old('organisation') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">E-Mail</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email"
                                       value="{{ old('email') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control"
                                       name="password_confirmation">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @stop
<link href="{{ asset('/css/carousel.css') }}" rel="stylesheet">
