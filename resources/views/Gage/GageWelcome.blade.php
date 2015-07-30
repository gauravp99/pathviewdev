@extends('GageApp')

@section('content')

    <div class="content">
        <div class="rows">
            <div class="col-sm-12">
                <div class="col-sm-8 corousel-content ">
                    Welcome GAGE Web
                </div>
                <div class="col-sm-4">
                    <a href="/gage">
                        <button type="button"  class="btn btn-primary btn-lg GetStarted" >
                            Quick Start
                        </button>
                    </a>
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
