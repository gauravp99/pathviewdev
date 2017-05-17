@extends('app')

@section('content')
@include('navigation')
    <script src="js/ChartNew.js"></script>
    <div class='col-md-2-result sidebar col-md-offset-2'>
    <div class="content">
        <div class="rows">
            <div class="col-sm-12">
                <div class="col-sm-8 corousel-content ">
                    <h2 class="homepage-heading">Introduction</h2>

                    <p class="content1">
                        <a href="http://www.bioconductor.org/packages/release/bioc/html/pathview.html" target="_blank">Pathview</a>
                        maps, integrates and renders a wide variety of biological data on relevant pathway graphs. Here
                        is a detailed <a href="data/pathviewIntro_flyer.pdf" target="_balnk">overview</a>.
                        Pathview Web provides easy interactive access, and generates high quality, hyperlinked graphs.
                        Pathview package is written in R/Bioconductor, this web interface is built on PHP with Laravel
                        Framework and R.
                    </p>

                    <h2 class="homepage-heading">Credits</h2>

                    <p class="content1">
                        Pathview web is designed and developed by Weijun Luo and his team (Gaurav Pant, Yeshvant Kumar Bhavnasi). We also get
                        support from Steven Blanchard, Cory Brouwer and the whole <a href="https://bioservices.uncc.edu/" target="_blank">Bioinformatics Service Division (BiSD)</a>
                        at UNC Charlotte.
                    </p>





                    <h2 class="homepage-heading">Disclaimer</h2>

                    <p class="content1">
                        Pathview is an open source software package distributed under <a
                                href="http://www.gnu.org/licenses/gpl-3.0.html" target="_blank">GPLv3</a>. Pathview
                        downloads and uses KEGG data. Academic users may freely use the KEGG website, but other uses may
                        require a license agreement (details at <a href="http://www.kegg.jp/kegg/legal.html"
                                                                   target="_blank">KEGG website</a>).
                        </br>
                        We made our best efforts when developing Pathview Web server. However, we DO NOT warrant nor assume any legal responsibility for the                         content and function of this server.
                    </p>

                    <h2 class="homepage-heading">Citations</h2>
                       @include('reference')


                        <h2 class="homepage-heading">Sponsors</h2>

                        <p class="content1">
                            This project is supported by an NSF ABI Development grant (Award # 1565030) and the Faculty Innovation Fund to Weijun Luo from the <a href="http://cci.uncc.edu/" target="_blank">College of Computing and Informatics at UNC Charlotte</a>. Sponsors also include  <a href="https://bioservices.uncc.edu/" target="_blank">BiSD</a> and <a href="http://bioinformatics.uncc.edu/" target="_blank">Department of Bioinformatics and Genomics</a>.
                            </br>

                        </p>
                    <div class="col-sm-10" align='center'>
                            <a href="https://nsf.gov/" target="_blank"><img src="images/nsf1.gif" width="15%" height="20%" ></a>
                            <a href="https://bioservices.uncc.edu/" target="_blank"><img src="images/servicesDivisionLogo.png" width="20%" height="20%"></a>
                            <a href="http://bioinformatics.uncc.edu/" target="_blank"><img src="images/BioinformaticsLogo.jpg" width="30%" height="30%"></a>
                    </div>

                </div>

                <div class="col-sm-4 leftsidebar">

                    <?php
                    if(Auth::user())
                    {
                    ?>
                    <a href="/analysis">
                        <button type="button" class="btn btn-primary btn-lg GetStarted ">
                            Quick Start
                        </button>
                    </a>
                    <a href="/gageIndex" hidden="">
                            <button type="button" class="btn btn-primary btn-lg GetStarted ">
                                Try GAGE
                            </button>
                        </a>

                    <?php
                    }
                    else{?>
                    <a href="/guest">
                        <button type="button" class="btn btn-primary btn-lg GetStarted ">
                            Quick Start
                        </button>
                    </a>
                    <?php }?>

                    <div class="panel panel-default">
                        <div class="panel-heading leftpanel">Usage Statistics</div>
                        <div class="panel-body">
                            <p>Pathview web</p>

                            <div id="graph" class="col-md-12">


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
        </div>
    </div>
    <script>
        var ctx = $("#myChart").get(0).getContext("2d");

        var opt2 = {

            canvasBordersWidth: 3,
            canvasBordersColor: "#205081",
            legend: true,
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
