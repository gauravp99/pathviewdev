@extends('app')
@section('content')
    @include('navigation')

    <h1>
        <div class="avtar"><img src={{asset('/images/avtar.png')}} height="102" width="102"></div>
    </h1>
    <p><b>User ID: {{ $user->id }}</b></p>
    <p><b>User Email: {{ $user->email }}</b></p>
    <p><b>Member since: {{ $user->created_at }}</b></p>


    <p> {!! form::open(array('url' => 'edit_post','method'=>'POST','files'=>true)) !!}
        {!!form::label('Name','Name:') !!}
        @if($user->name=="")
            {!!form::text('name',$user->email) !!}
        @else
            {!!form::text('name',$user->name) !!}
        @endif
        {!!form::label('email','Email:') !!}
        {!!form::text('email',$user->email) !!}
        <br/>
        <br/>
        <button type="submit" class="btn btn-primary" style="margin-left:17%;font-size: 18px;width:10%;">
           Submit
        </button>

        {!! form::close() !!}
    </p>







@stop