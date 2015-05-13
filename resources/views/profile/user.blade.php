@extends('app')
@section('content')
    @include('navigation')

    <h1>
        <div class="avtar"><img src={{asset('/images/avtar.png')}} height="102" width="102"></div>
        <a href="/edit_user/{{Auth::user()->id}}"><input type="button" class="styled-button-2" value="Manage Profile"/></a>
    </h1>
    <p><b>User ID: {{ $user->id }}</b></p>
    @if(($user->name)=="")
        <p><b>User Name: {{ $user->email }}</b></p>
    @else
        <p><b>User Name: {{ $user->name }}</b></p>
    @endif
    <p><b>User Email: {{ $user->email }}</b></p>
    <p><b>Member since: {{ $user->created_at }}</b></p>

@stop