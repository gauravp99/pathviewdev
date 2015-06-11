@extends('app')
@section('content')
    @include('navigation')
<div class="col-sm-7">
    <div class="col-sm-7 col-md-5">

        <div class="avtar"><img src={{asset('/images/avtar.png')}} height="102" width="102"></div>
        <a href="/edit_user/{{Auth::user()->id}}"><input type="button" class="styled-button-2" value="Manage Profile"/></a>

        </div>
    <div class="col-sm-12">

        <p><b>User ID: {{ $user->id }}</b></p>
    @if(($user->name)=="")
        <p><b>User Name: {{ $user->email }}</b></p>
    @else
        <p><b>User Name: {{ $user->name }}</b></p>
    @endif
    <p><b>User Email: {{ $user->email }}</b></p>
    <p><b>Member since: {{ $user->created_at }}</b></p>
    <?php
    $f = './all/' . $user->email;
    $io = popen('/usr/bin/du -sh ' . $f, 'r');
    $size = fgets($io, 4096);
    $size = substr($size, 0, strpos($size, "\t"));

    pclose($io);
    $size = 100 - intval($size);
    if($size < 10)
    {
    ?>
    <p class="alert alert-danger"><b>Remaining space {{ $size}} MB </b></p>
    <?php } else { ?>
    <p class="alert alert-info"><b>Remaining space {{ $size}} MB </b></p>

    <?php }?>
    </div>
</div>

@stop