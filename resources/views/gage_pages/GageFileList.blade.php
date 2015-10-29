@extends('GageApp')


@section('content')

<div class="col-md-12">
    <h1> Full list of file generated of the analysis: {{$_GET['id']}}</h1>
    <div class="col-md-8 col-lg-offset-2">
<?php

    $id = $_GET['id'];
    if(Auth::user())
        {
            $user = Auth::user()-> email;
        }
        else{
            $user = "demo";
        }

        $directory = public_path()."/all/".$user."/".$id;
        $contents = scandir($directory);
        $dir = substr($directory,strlen(public_path()));

foreach ($contents as $k => $v) {
    if(strcmp($v,'.')==0||strcmp($v,'..')==0||strcmp($v,'errorFile.Rout')==0||strcmp($v,'outputFile.Rout')==0||strcmp($v,'workenv.RData')==0)
    {
    }
    else
    {
        echo "<li><a target=\"_blank\" href=\"$dir/" . $v . "  \">$v</a></li>";
    }
}
    ?>
</div>
</div>
    @stop