@extends('app')

@section('content')
    <script>
        window.onresize = resizefunc;
        function resizefunc() {
            var canvas1 = document.getElementsByTagName('canvas')[0];
            var canvas2 = document.getElementsByTagName('canvas')[1];


            //canvas.width = document.getElementById("graph").offsetWidth;
            //canvas1.width = document.getElementById("graph").offsetWidth;
        }
    </script>
    <div class="content">
        <div class="rows">
            <div class="col-sm-12">
                <div class="col-sm-8">
                    <h2 class="marketing-hero-heading">
                        <a href="http://www.bioconductor.org/packages/release/bioc/html/pathview.html">Pathview</a>
                        maps, integrates and renders a wide variety of biological data
                        on relevant pathway graphs.</h2>


                </div>
            </div>
            <div class="col-sm-12">
                <div class="col-sm-8">
                    <h2 class="marketing-hero-heading">
                        Pathview Web provides easy interactive access, and generates high
                        quality, hyperlinked graphs.
                    </h2>
                </div>
                <div class="col-sm-4" style="">
                    <a href="/guest">
                        <button type="button" class="btn btn-primary btn-lg GetStarted  " data-toggle="modal">
                            Quick Start
                        </button>
                    </a>
                </div>

            </div>
            <div class="col-sm-12">


                <div class="marketing col-sm-8">

                    <div class="marketing-hero-inner">
                        <div id="myCarousel" class=" carousel slide col col-sm-12 " data-ride="carousel">
                            <!-- Indicators -->
                            <ol class="carousel-indicators">
                                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                                <li class="" data-target="#myCarousel" data-slide-to="1"></li>
                                <li class="" data-target="#myCarousel" data-slide-to="2"></li>
                                <li class="" data-target="#myCarousel" data-slide-to="3"></li>
                                <li class="" data-target="#myCarousel" data-slide-to="4"></li>
                            </ol>
                            <div class="carousel-inner" role="listbox">
                                <div class="item active">
                                    <img class="first-slide" src="images/fullimage1.png" alt="First slide">

                                </div>
                                <div class="item">
                                    <img class="second-slide" src="images/b.png" alt="Second slide">

                                </div>
                                <div class="item">
                                    <img class="third-slide" src="images/c.png" alt="Third slide">

                                </div>
                                <div class="item">
                                    <img class="third-slide" src="images/d.png" alt="Third slide">
                                </div>
                                <div class="item">
                                    <img class="third-slide" src="images/e.jpg" alt="Third slide">

                                </div>
                                <a href="#myCarousel" role="button" data-slide="next">
                                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a href="#myCarousel" role="button" data-slide="next">
                                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>

                        </div>

                    </div>


                    <div class="homepage-content-inner col-sm-12">
                        <div class="homepage-content-image-container">
                            <div class="col-sm-12 ">
                                <h2 class="homepage-heading">Citations</h2>

                                <div>
                                    <p class="content1">Luo W, Brouwer C. Pathview: an R/Biocondutor package for
                                        pathway-based data integration and visualization. Bioinformatics, 2013,
                                        29(14):1830-1831, doi:
                                        <a href="http://bioinformatics.oxfordjournals.org/content/29/14/1830"
                                           target="_blank">
                                            <u>10.1093/bioinformatics/btt285</u></a>
                                    </p>

                                </div>


                            </div>
                        </div>
                    </div>

                    <div class="homepage-content-inner col-sm-12 ">
                        <div class="homepage-content-image-container">
                            <div class="col-sm-8 ">
                                <h2 class="homepage-heading">Contact</h2>

                                <p class="contact">Project Contact: <a
                                            href="mailto:luo_weijun@yahoo.com">luo_weijun@yahoo.com</a></p>

                                <p class="contact"> Report Issues : <a
                                            href="mailto:byeshvant@hotmail.com">byeshvant@hotmail.com</a>


                                    <script type="text/javascript">
                                        window.onLoad = function () {
                                            init();


                                        };


                                        function init() {


                                            var ctx = $("#myChart").get(0).getContext("2d");
                                            var opt2 = {

                                                canvasBordersWidth: 3,
                                                canvasBordersColor: "#205081",
                                                //graphTitle : "Pathview Web",
                                                inGraphDataShow: true,
                                                annotateDisplay: true,
                                                legend: true,
                                                inGraphDataShow: false,
                                                annotateDisplay: true,
                                                graphTitleFontSize: 18,
                                                logarithmic: true
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
//graphTitle : "Bioc Package",
                                            var opt1 = {

                                                canvasBordersWidth: 3,
                                                canvasBordersColor: "#205081",
                                                scaleStartValue: 10,
                                                legend: true,
                                                inGraphDataShow: true,
                                                annotateDisplay: true,
                                                inGraphDataShow: false,
                                                annotateDisplay: true,
                                                animationEasing: "easeOutBounce",
                                                graphTitleFontSize: 18


                                            };
                                            var myBarChart1 = new Chart(ctx1).Bar(data1, opt1);

                                        }

                                    </script>

                            </div>

                        </div>
                    </div>
                </div>


                <div class="col-md-4">

                    <div class="panel panel-default">
                        <div class="panel-heading">Login</div>
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
                                        <button type="submit" class="btn btn-primary"
                                                style="font-size: 16px;width: 100%;">Login
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <a class="btn btn-link" href="{{ url('/password/email') }}">Forgot Your
                                            Password?</a>
                                    </div>
                                </div>

                            </form>


                        </div>


                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary btn-lg register" style="border-radius: 1px;"
                                data-toggle="modal" data-target="#myModal">
                            Click here to Register
                        </button>

                        <!-- Modal -->
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
                                                <label class="col-md-4 control-label">E-Mail Address</label>

                                                <div class="col-md-6">
                                                    <input type="email" class="form-control" name="email"
                                                           value="{{ old('email') }}">
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
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" style="align:left;">Usage Statistics</div>
                        <div class="panel-body">
                            <p style="text-align:left;">Pathview web</p>
                            <div id="graph" class="col-md-12">
                                <section>

                                    <table style="margin:5px;font-size: 12px;">
                                        <tr>

                                            <td>
                                                <div style="width:80%;background-color:#C1DBF0; width=100%;">
                                                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                                </div>
                                            </td>
                                            <td>Graphs</td>
                                            <td>
                                                <div style="width:80%;background-color:#D4DBE0; width=100%;">
                                                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                                </div>
                                            </td>
                                            <td>IP's</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>46</td>
                                            <td></td>
                                            <td>5</td>
                                        </tr>

                                    </table>
                                    <article>
                                        <canvas id="myChart" width="450" height="250">
                                        </canvas>
                                    </article>
                                </section>
                            </div>
                            <p style="text-align:left;"> Bioc Package</p>
                            <div id="graph1" class="col-md-12">



                                <section>

                                    <table style="margin:5px;font-size: 12px;">
                                        <tr>

                                            <td>
                                                <div style="width:80%;background-color:#C1DBF0; width=100%;">
                                                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                                </div>
                                            </td>
                                            <td>Downloads</td>
                                            <td>
                                                <div style="width:80%;background-color:#D4DBE0; width=100%;">
                                                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                                </div>
                                            </td>
                                            <td>IP's</td>
                                        <tr>
                                            <td></td>
                                            <td>&#8776;160000</td>
                                            <td></td>
                                            <td>&#8776;11123</td>
                                        </tr>
                                        </tr>

                                    </table>
                                    <article>
                                        <canvas id="myChart1" width="450" height="250">
                                        </canvas>
                                    </article>
                                </section>
                            </div>

                        </div>
                    </div>
                    <script>
                        var canvas = document.getElementsByTagName('canvas')[0];
                        canvas.width = document.getElementById("graph").offsetWidth;
                        var canvas1 = document.getElementsByTagName('canvas')[1];
                        canvas1.width = document.getElementById("graph1").offsetWidth;


                    </script>

                    <style>


                        td, th {
                            padding: 5px;
                            text-align: center;

                        }

                        table {
                            border: 3px solid #8C8484;
                            padding: 5px;
                            text-align: center;
                            margin-top: 10px;
                        }

                        th {
                            background-color: #205081;
                            color: white;
                        }
                    </style>


                </div>

            </div>


        </div>





@stop