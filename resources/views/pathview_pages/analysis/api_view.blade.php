@extends('app')
@section('content')
   @include('navigation')
    <div class='col-md-2-result sidebar col-md-offset-2'>
    <div class="col-md-12 content" style="text-align: center;">

        <h1><b>API Query String</b></h1>
        <h1 class="arg_content">Short Format (Default parameters skipped)</h1>
        </br>
        <p style="font-size: 18px; margin-left: 100px;">
        {{$invocation_short}}
        </p>
        </br>
        <h1 class="arg_content">Long Format</h1>
        </br>
        <p style="font-size: 18px; margin-left: 100px;">
        {{$invocation}}
         </p>
        </br>
        </br>
       <p><b>Note:</b> Complete Path of the file where the file is actually located has to be provided for gene_data and cpd_data. For eg. gene_data path can be given as --gene_data /home/Users/pathview/gse16873.d3.txt' </p>
  </div>
</div>
@stop
