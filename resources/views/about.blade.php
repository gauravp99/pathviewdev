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
                        scaleOverride: true,
                        scaleSteps: 6,
                        scaleStepWidth: 200,
                        scaleStartValue: 250,
                        scaleLabel: "<%=value%>",
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
                    <div class="panel-heading">Usage Statistics</div>
                    <div class="panel-body">
                        <div class="panel-body">
                            <p style="text-align:left;">Pathview web</p>

                            <div id="graph" class="col-md-12">
                                <section>

                                    <table style="margin:5px;font-size: 12px;">
                                        <tr>

                                            <td>
                                                <div style="width:80%;background-color:#C1DBF0;">
                                                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                                </div>
                                            </td>
                                            <td>Graphs:</td>
                                            <td><?php echo $web_dnld_cnt ?></td>
                                            <td>
                                                <div style="width:80%;background-color:#D4DBE0;">
                                                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                                </div>
                                            </td>
                                            <td>IP's:</td>
                                            <td><?php echo $web_ip_cnt ?></td>
                                        </tr>
                                    </table>
                                    <article>
                                        <canvas id="myChart" width="450" height="200">
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
                                                <div style="width:80%;background-color:#C1DBF0;">
                                                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                                </div>
                                            </td>
                                            <td>Downloads:</td>
                                            <td>&#8776;<?php echo $bioc_dnld_cnt ?></td>
                                            <td>
                                                <div style="width:80%;background-color:#D4DBE0;">
                                                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                                </div>
                                            </td>
                                            <td>IP's:</td>
                                            <td>&#8776;<?php echo $bioc_ip_cnt ?></td>

                                        </tr>

                                    </table>
                                    <article>
                                        <canvas id="myChart1" width="450" height="200">
                                        </canvas>
                                    </article>
                                </section>
                            </div>

                        </div>


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


@stop