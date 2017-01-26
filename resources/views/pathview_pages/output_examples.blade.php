@extends('app')
@section('content')
@include('navigation')
    <div class='col-md-2-result sidebar col-md-offset-2'>
        <div class="col-sm-12">

           <h1 class="arg_content">Output Graph as in all examples</h1>
                <p>
                  The main results/outputs are pathway graphs with user data integrated (example figures below).  Pathview generates graphs in two styles: either the native KEGG view or the Graphviz view. The former renders user data on native KEGG pathway graphs (raster images), and is more interpretable with abundant context and meta-data. The latter layouts pathway graph using Graphviz engine (vector images), and provides better control over node/edge attributes and graph topology. In browser, native KEGG graphs are interactive with hover and hyperlinked annotations.
                </p>
            <section id="kegg_view">
                <h1 class="arg_content">KEGG view (PNG files, as in <a href="/example1">Example 1</a>)</h1>

                </br>
                <div class="col-sm-12">
                    <img src="data/kegg_view.png" style="width: 100%;">
                     </br>
                </div>
		     <p style="font-size: 15px; margin-left: 20px;">
                       <i>*Legend and annotations added for demonstration. </i>
                     </p>
            </section>
        </div>

        <div class="col-sm-12">

            <section id="graphviz_view">
                <h1 class="arg_content">Graphviz view (PDF files, as in <a href="/example2">Example 2</a>)</h1>
                </br>
                <div class="col-sm-12">
                    <img src="data/graphviz_view.png" style="width: 100%;">
                </div>
		<p style="font-size: 15px; margin-left: 20px;">
                  <i>*Legend and annotations added for demonstration. </i>
                </p>
            </section>

        </div>
        <div class="col-sm-12">
            <section id="example4_view">
                <h1 class="arg_content">Pathway analysis results (as in  <a href="/example4">Example 4</a>)</h1>
                <p>
When automatic pathway selection is chosen, pathway analysis will be done before data visualization on selected pathways (<Example 4>). The pathway analysis statistics will be returned, and all analysis results will be included in the downloadable zipped folder.
                </p>
                </br>
            </section>
        </div>

     </div>
@stop
