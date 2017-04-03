@extends('app')
@section('content')
@include('navigation')
    <div class='col-md-2-result sidebar col-md-offset-2'>
        <div class="col-sm-12">




           <h1 class="arg_content" >Introduction</h1>
                 <p>
		 Pathview is an <a href="http://www.bioconductor.org/packages/release/bioc/html/pathview.html">R/Bioconductor package</a> that maps, integrates and renders a wide variety of biological data on relevant pathway graphs. Users just need to supply their gene or compound data and specify the target pathway. Pathview automatically downloads the pathway graph data, parses the data file, maps user data to the pathway, and renders pathway graph with the mapped data. Please check the <a href="/api_examples">example analyses</a> and the input/out section below for examples and more details. You may always go through the <a href="http://www.bioconductor.org/packages/release/bioc/vignettes/pathview/inst/doc/pathview.pdf">package vignette</a> for a tutorial.   </p>
       <p>
                 
                 Pathview Web server extends the core functions of Pathview with:
                 </br>
                 -a simple intuitive <a href="/analysis">graphical user interface</a>
                 </br>
                 -fast and programmatic access through  <a href="/api_tutorial">RESTful API</a>
                 </br>
                 -complete pathway analysis workflow supporting multiple omics data and integrated analysis (check:  <a href="/example4">Example 4</a>)
                 </br>
                 -interactive and hyperlinked results graphs for better data interpretation
                 </br>
                 -most complete and up-to-date pathway data via regular database synchronization
                 </br>
                 -open access to all analyses and resources
                 </br>
                 -analysis history and data sharing via  <a href="/register">free registered user accounts</a>
                 </br>
                 -complete online help and documentation
                 </br>
                 -multiple quick-start  <a href="/example1">example analyses</a>
                 </br>
                 
                 </p>



           <h1 class="arg_content" >Input</h1>
            <p>
	     The web form in the <a href="/analysis">analysis page</a> collects all user inputs. 
             The most important user inputs and the only options with no default are the user data to be visualized or analyzed. 
	     There are two major categories of input data: 
             </br>
             1) gene data cover any data that map to unique gene IDs including genes, transcripts, 
	     genomic loci, proteins, enzymes and their attributes, 
             </br>
             2) compound data cover any data that map to unique compound IDs including compounds, metabolites, drugs, small 
              molecules and their attributes. Such generic definitions of gene and compound data allow Pathview to work 
              with a wide range of data types. All user options are self-explanatory and fully described online with examples.
           </p>
           <h1 class="arg_content" >Output</h1>
                <p>
                  The main results/outputs are pathway graphs with user data integrated (example figures below).  Pathview generates graphs in two styles: either the native KEGG view or the Graphviz view. The former renders user data on native KEGG pathway graphs (raster images), and is more interpretable with abundant context and meta-data. The latter layouts pathway graph using Graphviz engine (vector images), and provides better control over node/edge attributes and graph topology. In browser, native KEGG graphs are interactive with hover and hyperlinked annotations.
                </p>
            <section id="kegg_view">

		<h1 class="arg_content" style="font-size: 30px; padding-top: 10px; padding-bottom: 10px;">KEGG view (PNG files, as in <a href="/example1">Example 1</a>)</h1>
                Input <a href="data/hsa04540.gse16873.png" target=_blank>image</a> and Input <a href="data/hsa04540.xml" target=_blank>File</a>.
                </br>

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
                Input <a href="data/hsa00565.gse16873.png" target=_blank>image</a> and Input <a href="data/hsa00565.xml" target=_blank>File</a>.
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
            </section>
        </div>
        <div class="col-sm-12">
          <h1 class="arg_content">Help</h1>
            <p>
	      We also provide a comprehensive help system with the server. 
              The help system has four components: 
               </br>
               1) a centralized <a href="/output_examples">help pages</a>, including documentation of all user options, input and output description; 
               </br>
               2) multiple <a href="/example1">example analyses</a> with input data preloaded and options preset, plus dedicated tutorials; and 
               </br>
	       3) the help button next to each user option in the <a href="/analysis">analysis pages</a>, which directly links to its help page; 
               </br>
               4) extra user support channels through <email> (emailto url) and the <a href="/faq">question page</a>.
           </p>

          </div>
        <div class="col-sm-12">

            <section id="refrence">

                <h1 class="arg_content">Reference</h1>

                <p class="content1"><i>Luo W, Brouwer C. Pathview: an R/Biocondutor package for
                        pathway-based data integration and visualization. Bioinformatics, 2013,
                        29(14):1830-1831, doi:
                        <a href="http://bioinformatics.oxfordjournals.org/content/29/14/1830.full" target="_blank">
                            <u>10.1093/bioinformatics/btt285</u></a>
                    </i></p>
            </section>

        </div>

        <div class="col-sm-12">

            <section id="contact">

                <h1 class="arg_content">Contact</h1>

                <p class="contact">Email us: <a href="mailto:pathomics@gmail.com">pathomics@gmail.com</a></p>
            </section>

        </div>

     </div>

@stop
