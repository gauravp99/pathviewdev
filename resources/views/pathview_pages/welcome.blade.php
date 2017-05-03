@extends('app')
@section('content')


    @if (session('message'))
       <h1 class="success" style="color:rgb(65, 134, 58);"> {{ session('message') }} </h1>
    @endif
    <div class="content">
        <div class="rows">
            <script src="js/ChartNew.js"></script>
            <div class="col-sm-12">
                <div class="col-sm-8">
                    <h2 class="marketing-hero-heading">
                        <a href="http://www.bioconductor.org/packages/release/bioc/html/pathview.html" target="_blank">Pathview</a>
                        maps, integrates and renders a wide variety of biological data on relevant pathway graphs.
                    </h2>
                </div>
                <div class="col-sm-4">
                    <a href="/guest">
                        <button type="button" class="btn btn-primary btn-lg GetStarted">
                            Quick Start
                        </button>
                    </a>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="col-sm-8">
                    <h2 class="marketing-hero-heading">
                        Pathview Web provides easy interactive access, and generates high quality, hyperlinked graphs.
                    </h2>
                </div>
                <div class="col-sm-4" hidden="">
                    <a href="/gageIndex">
                        <button type="button" class="btn btn-primary btn-lg GetStarted">
                           Try GAGE
                        </button>
                    </a>

                </div>
            </div>

            <div class="col-sm-12">
                <div class="col-sm-8 corousel-content">
                    <div class="marketing-hero-inner">
                        <div id="myCarousel" class=" carousel slide col col-sm-12 " data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                                <li class="" data-target="#myCarousel" data-slide-to="1"></li>
                                <li class="" data-target="#myCarousel" data-slide-to="2"></li>
                                <li class="" data-target="#myCarousel" data-slide-to="3"></li>
                                <li class="" data-target="#myCarousel" data-slide-to="4"></li>
                                <li class="" data-target="#myCarousel" data-slide-to="5"></li>
                            </ol>
                            <div class="carousel-inner" role="listbox">

                                <div class="item active">
                                    <img class="sixth-slide" src="images/sixthslide.png" alt="Sixth slide">
                                </div>
                                <div class="item" style="margin: 0;" >
                                    <img class="first-slide" src="images/firstslide.png" alt="First slide">
                                </div>
                                <div class="item">
                                    <img class="second-slide" src="images/secondslide.png" alt="Second slide">
                                </div>
                                <div class="item">
                                    <img class="third-slide" src="images/thirdslide.png" alt="Third slide">
                                </div>
                                <div class="item">
                                    <img class="third-slide" src="images/fourthslide.png" alt="Fourth slide">
                                </div>
                                <div class="item">
                                    <img class="third-slide" src="images/fifthslide.jpg" alt="Fifth slide">
                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="col-sm-12 ">
                        <h2 class="homepage-heading">Citations:</h2>

                        <p class="content1"><i>
                                Luo W, Brouwer C. Pathview: an R/Biocondutor package for pathway-based data integration
                                and visualization. Bioinformatics, 2013, 29(14):1830-1831, doi:
                                <a href="http://bioinformatics.oxfordjournals.org/content/29/14/1830.full"
                                   target="_blank">10.1093/bioinformatics/btt285</a>
                            </i></p>

                        <p class="content1">
                        </p>
                        <br>

                        <p class="content1">
                            Please cite our paper if you use the Pathview package or this website. In addition, please
                            cite this website if you use it. This will help the Pathview project in return.
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
                        <div class="panel-heading leftpanel ">Login</div>
                        <div class="panel-body">
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

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

                        <button type="button" class="btn btn-primary btn-lg register clickRegister" data-toggle="modal"
                                data-target="#myModal">
                            Click here to Register
                        </button>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading leftpanel">Usage Statistics</div>
                        <div class="panel-body">
                            <p>Pathview web</p>

                            <div id="graph1" class="col-md-12">


                                <table class="tables">
                                    <tr>

                                        <td>
                                            <div class="bar12">
                                                &nbsp
                                            </div>
                                        </td>
                                        <td class="tdContent">Analyses:</td>
                                        <td><?php echo $web_dnld_cnt ?></td>
                                        <td>
                                            <div class="bar11">
                                                &nbsp
                                            </div>
                                        </td>
                                        <td class="tdContent">IP's:</td>
                                        <td><?php echo $web_ip_cnt ?></td>
                                    </tr>

                                </table>
                            </div>
                            <canvas id="myChart"></canvas>
                            <p> Bioc Package</p>

                            <div id="graph1" class="col-md-12">
                                <table class="tables">
                                    <tr>
                                        <td>
                                            <div class="bar2">
                                                &nbsp&nbsp
                                            </div>
                                        </td>

                                        <td class="tdContent">Downloads:</td>
                                        <td><?php echo $bioc_dnld_cnt ?></td>


                                        <td>
                                            <div class="bar1">
                                                &nbsp&nbsp
                                            </div>
                                        </td>

                                        <td class="tdContent">IP's:</td>
                                        <td><?php echo $bioc_ip_cnt ?></td>

                                    </tr>
                                </table>
                            </div>
                            <canvas id="myChart1"></canvas>
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
                            <h2 class="modal-title " id="myModalLabel">Register</h2>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal" role="form" method="POST"
                                  action="{{ url('/register') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                <div class="form-group">
                                    <label class="col-md-4 control-label">Name</label>

                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="name" value="{{ old('name') }}">
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
                                        <input type="password" class="form-control" name="password_confirmation">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn col-md-6 btn-primary" style="font-size: 20px">
                                            Register
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <div class="modal-footer">
                            <button type="button" id="123" class="btn btn-default" data-dismiss="modal">Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </div>
    <link href="{{ asset('/css/carousel.css') }}" rel="stylesheet">
    <script>


        var ctx = $("#myChart").get(0).getContext("2d");

        var opt2 = {

            canvasBordersWidth: 3,
            canvasBordersColor: "#205081",
            legend: true,
            scaleStepWidth: 500,
            graphTitleFontSize: 18,
            logarithmic: true,
            responsive: true
        };

        var data = {

            labels: JSON.parse('<?php echo JSON_encode($months);?>'),
            datasets: [
                {
                    fillColor: "rgba(220,220,220,0.5)",
                    strokeColor: "rgba(220,220,220,1)",
                    pointColor: "rgba(220,220,220,1)",
                    pointStrokeColor: "#fff",
                    data: JSON.parse('<?php echo JSON_encode($usage);?>')
                    //title:"No of Graphs"
                },
                {
                    fillColor: "rgba(151,187,205,0.5)",
                    strokeColor: "rgba(151,187,205,1)",
                    pointColor: "rgba(151,187,205,1)",
                    pointStrokeColor: "#fff",
                    data: JSON.parse('<?php echo JSON_encode($ip);?>')
                    // title:"Unique IP's"
                }
            ]
        };

        var myBarChart = new Chart(ctx).Bar(data, opt2);

        var ctx1 = $("#myChart1").get(0).getContext("2d");

        var data1 = {
            labels: JSON.parse('<?php echo JSON_encode($bioc_months);?>'),
            datasets: [
                {
                    fillColor: "rgba(220,220,220,0.5)",
                    strokeColor: "rgba(220,220,220,1)",
                    pointColor: "rgba(220,220,220,1)",
                    pointStrokeColor: "#fff",
                    data: JSON.parse('<?php echo JSON_encode($bioc_downloads);?>')
                },
                {
                    fillColor: "rgba(151,187,205,0.5)",
                    strokeColor: "rgba(151,187,205,1)",
                    pointColor: "rgba(151,187,205,1)",
                    pointStrokeColor: "#fff",
                    data: JSON.parse('<?php echo JSON_encode($bioc_ip);?>')
                }
            ]
        };

        var opt1 = {

            canvasBordersWidth: 3,
            canvasBordersColor: "#205081",
            scaleOverride: true,
            scaleSteps: 4,
            scaleStepWidth: 500,
            scaleStartValue: 0,
            scaleLabel: "<%=value%>",
            legend: true,
            graphTitleFontSize: 18,
            responsive: true


        };
        var myBarChart1 = new Chart(ctx1).Bar(data1, opt1);

    </script>
@stop
