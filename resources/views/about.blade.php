@extends('app')

@section('content')
    <div class="content">
        <div class="col-sm-12">
            <div class="col-sm-8 corousel-content ">
                <h2 class="homepage-heading">About</h2>

                <p class="content1">
                    Pathview is a tool set for pathway based data integration and
                    visualization. It maps, integrates and renders a wide variety of
                    biological data on relevant pathway graphs.
                    Pathview Web Interface provides easy access, and generates high quality,
                    hyperlinked pathway graphs with user data.
                </p>
            </div>
            <div class="col-sm-4 leftsidebar">
                <a href="/guest">
                    <button type="button" style="margin-bottom: 6%" class="btn btn-primary btn-lg GetStarted " >
                        Quick Start
                    </button>
                </a>
                <div class="panel panel-default">
                    <div class="panel-heading leftpanel" >Usage Statistics</div>
                    <div class="panel-body">
                        <p >Pathview web</p>

                        <div id="graph" class="col-md-12">


                            <table class="tables" >
                                <tr>

                                    <td>
                                        <div class="bar1" >
                                            &nbsp&nbsp
                                        </div>
                                    </td>
                                    <td width="">Graphs:</td>
                                    <td><?php echo $web_dnld_cnt ?></td>
                                    <td>
                                        <div class="bar2">
                                            &nbsp&nbsp
                                        </div>
                                    </td>
                                    <td>IP's:</td>
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
                                        <div class="bar1" >
                                            &nbsp&nbsp
                                        </div>
                                    </td>

                                    <td>Downloads:</td>

                                    <td>&#8776;<?php echo $bioc_dnld_cnt ?></td>

                                    <td>
                                        <div class="bar2">
                                            &nbsp&nbsp
                                        </div>
                                    </td>

                                    <td>IP's:</td>

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


@stop