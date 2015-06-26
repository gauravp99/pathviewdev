@extends('app')

@section('content')

    <?php
echo '<h1 class="alert alert-danger">'.Session::get('error').'</h1>';
    if (strcmp(Session::get('error'), "error") == 0) {
        echo '<h1 class="alert alert-danger">Sorry!! Seems to be an issue with RServ Connection.</h1>';
    } else {
        echo ' <h1 class="alert alert-danger">Sorry!! Seems to be an Issue with Input file. Please check from your side it is according to the specification.</h1>';
    } ?>

    <h1 class="alert alert-info">We are Working on the web page making more informative</h1>

@stop

