@extends('GageApp')

@section('content')
    <div class="content">
        <div class="rows">
            <div class="col-sm-12">
                <div class="col-sm-8 corousel-content ">
                    About GAGE Web
                </div>
                <div class="col-sm-4">
                    <div class="panel panel-default">
                        <div class="panel-heading leftpanel" >Usage Statistics</div>
                        <div class="panel-body">
                            <p >Pathview web</p>

                            <div id="graph1" class="col-md-12">


                                <table class="tables" >
                                    <tr>

                                        <td>
                                            <div class="bar12">
                                                &nbsp
                                            </div>
                                        </td>
                                        <td class="tdContent">Gage Analysis:</td>
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

@stop