@extends('app')

@section('content')
    <div class="content">
        <div class="col-sm-12">
            <div class="col-sm-8 ">
                <h2 class="homepage-heading">About</h2>

                <p class="content1">
                    Pathview is a tool set for pathway based data integration and
                    visualization. It maps, integrates and renders a wide variety of
                    biological data on relevant pathway graphs.
                    Pathview Web Interface provides easy access, and generates high quality,
                    hyperlinked pathway graphs with user data.
                </p>
            </div>
            <div class="col-sm-4 ">
                <a href="/guest">
                    <button type="button" class="btn btn-primary btn-lg GetStarted" style="margin-top: 70px;"
                            data-toggle="modal">
                        Quick Start
                    </button>
                </a>
            </div>
            <div class="marketing col-sm-8">
                <div class="homepage-content-inner col-sm-12">

                    <div class="homepage-content-image-container">

                    </div>
                </div>
            </div>
            <div class="marketing col-sm-8">

                <h2 class="homepage-heading"> Usage Statistics</h2>

                <div class="marketing col-sm-6">
                    <section>
                        <article>
                            <canvas id="myChart" width="400" height="300">
                            </canvas>
                        </article>
                        <table style="margin:5px;margin-left: 40%;font-size: 12px;border:none;">
                            <tr>
                                <p style="text-align:left;position:absolute;">Pathview web</p>
                                <td>
                                    <div style="width:80%;background-color:#C1DBF0; width=100%;">
                                        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                    </div>
                                </td>
                                <td>No. of Graphs</td>
                                <td>
                                    <div style="width:80%;background-color:#D4DBE0; width=100%;">
                                        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                    </div>
                                </td>
                                <td>No. of unique IP's</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>46</td>
                                <td></td>
                                <td>5</td>
                            </tr>

                        </table>
                    </section>
                </div>
                <div class="marketing col-sm-6">
                    <section>
                        <article>
                            <canvas id="myChart1" width="400" height="300">
                            </canvas>
                        </article>
                        <table style="margin:5px;margin-left: 40%;font-size: 12px;">
                            <tr>
                                <p style="text-align:left;position:absolute;"> Bioc Package</p>
                                <td>
                                    <div style="width:80%;background-color:#C1DBF0; width=100%;">
                                        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                    </div>
                                </td>
                                <td>No. of Downloads</td>
                                <td>
                                    <div style="width:80%;background-color:#D4DBE0; width=100%;">
                                        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                    </div>
                                </td>
                                <td>No. of unique IP's</td>
                            <tr>
                                <td></td>
                                <td>&#8776;160000</td>
                                <td></td>
                                <td>&#8776;11123</td>
                            </tr>
                            </tr>

                        </table>
                    </section>
                </div>
            </div>
            <style>


                td, th {
                    border: 3px solid #8C8484;
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

                        logarithmic: true,
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
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}">
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
                                    <button type="submit" class="btn btn-primary" style="font-size: 16px;width: 100%;">
                                        Login
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


                </div>

            </div>


        </div>






@stop