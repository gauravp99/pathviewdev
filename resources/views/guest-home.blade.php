@extends('app')

@section('content')

    @include('navigation')
    <div class="panel panel-default">
        <div class="panel-heading">Guest Login</div>
        <div class='col-md-2-result sidebar col-md-offset-2'>

        <div class="panel-body">


            <p class="alert alert-danger">Alert!! You are on Guest Login and Your analysis  are not saved.</p>
            <p class="alert alert-success">If you want your analysis saved, please create a account. it's Free</p>

            <!-- Button trigger modal -->
                <div class="col-sm-4 col-md-offset-2">
            <button type="button" class="  btn btn-primary btn-lg register" style="border-radius: 1px;"
                    data-toggle="modal" data-target="#myModal">
                Click here to Register
            </button>
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
                                    <label class="col-md-4 control-label">E-Mail</label>

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
    </div>
            </div>

        </div>
        </div>

@stop