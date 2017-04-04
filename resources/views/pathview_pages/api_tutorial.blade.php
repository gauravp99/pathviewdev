@extends('app')
@section('content')


<style>
table, td, th {
    border: 1px solid black;
}

table {
    border-collapse: collapse;
    width: 100%;
}

th {
    height: 50px;
}
td {
    text-align: left;
   }

.details,
.show,
.hide:target {
  display: none;
}
.hide:target + .show,
.hide:target ~ .details {
  display: block;
}

</style>

@include('navigation')
    <div class='col-md-2-result sidebar col-md-offset-2'>
    <div class="col-md-12 content" style="text-align: center;">


        <h1><b>Pathview API Documentation</b></h1>
        <h1 class="arg_content">Get Started</h1>
        <p>Pathview API (url) is a bash script. It depends on cURL which is already pre-installed with  most Unix/Linux/Mac systems. cURL can be download  <a href='https://curl.haxx.se/download.html'>here</a> if needed. To get started:</p>
	<p>1. Download API with cURL
        </br>
        <p style="font-size: 15px; margin-left: 50px;">curl -O https://pathview.uncc.edu/scripts/pathviewapi.sh </p>
	<p>2. Modify access of the API </p>
        <p style="font-size: 15px; margin-left: 50px;">chmod +x ./pathviewapi.sh </p>
	<p>3. Ready to go, please check the following sections for usage</p>
<div>

        <h1 class="arg_content">Synopsis</h1>
        <p> Basic Usage </p>
        <p style="font-size: 15px; margin-left: 50px;"> ./pathviewapi.sh options --gene_data | --cpd_data [--gene_id] [-- cpd_id] [--pathway_id] ... [--other options] </p>
        <p> Common Usages (Remove annotation in parentheses in real use)</p>
        <p>&nbsp;&nbsp;&nbsp;Gene data:</p>
        <p style="font-size: 15px; margin-left: 50px;">./pathviewapi.sh --gene_data your/gene/data/file --species hsa (KEGG species code) --gene_id ENTREZ (gene ID type) --pathway_id 00640 (KEGG pathway ID) </p>
        <p>&nbsp;&nbsp;&nbsp;Compound data:</p>
         <p style="font-size: 15px; margin-left: 50px;">./pathviewapi.sh --cpd_data your/ cpd/data/file -- cpd_id KEGG (compound ID type) --pathway_id 00640 (KEGG pathway ID) </p>
        
        <p>&nbsp;&nbsp;&nbsp;Gene and Compound data:</p>
        <p style="font-size: 15px; margin-left: 50px;">./pathviewapi.sh --gene_data your/gene/data/file --gene_id ENTREZ (gene ID type) --cpd_data your/ cpd/data/file -- cpd_id KEGG (compound ID type) --pathway_id 00640 (KEGG pathway ID)
                </p>
        <p>&nbsp;&nbsp;&nbsp;Pathway analysis with Gene and Compound data (can be one of them):</p>
        <p style="font-size: 15px; margin-left: 50px;">./pathviewapi.sh --gene_data your/gene/data/file --gene_id ENTREZ (gene ID type) --cpd_data your/ cpd/data/file -- cpd_id KEGG (compound ID type) --auto_sel T
                </p>
        </br>
        <p>Help</p>
        <p style="font-size: 15px; margin-left: 50px;">./pathviewapi.sh --help </p>
        <p>Extras</p>
        <p>&nbsp;&nbsp;&nbsp;Detailed description of the arguments, click <a href="#options" >here</a></p>
        <p>&nbsp;&nbsp;&nbsp;Specific example analysis, click <a href="api_examples" target="_blank" >here</a></p>
        <p>&nbsp;&nbsp;&nbsp;Intuitive API query generator based on the GUI Web form, click <a href="analysis_api" target="_blank">here</a></p>
        <p>&nbsp;&nbsp;&nbsp;For extra issues, please contact  <a href="mailto:pathomics@gmail.com">pathomics@gmail.com</a></p>



</br>
        <div class="col-md-12"></div>
        <div class="col-md-12 content">
            <h1 class="arg_content">Example Analysis</h1>

            <div class="col-md-6">
                <div style="height:70px"><h2 style="text-align:left">Multiple Sample KEGG View</h2></div>

                <p>This example shows the multiple sample/state integration with Pathview KEGG view.</p>

                <p><a class="btn btn-default" href="api_examples#example1" role="button">View details »</a></p>
            </div>
            <div class="col-md-6">
                <div style="height:70px"><h2 style="text-align:left">Multiple Sample Graphviz View</h2></div>

                <p>This example shows the multiple sample/state integration with Pathview Graphviz view. </p>

                <p><a class="btn btn-default" href="api_examples/#example2" role="button">View details »</a></p>
            </div>
        </div>
        <div class="col-md-12 content">
            <div class="col-md-6">
                <div style="height:70px"><h2 style="text-align:left">ID Mapping</h2></div>

                <p>This example shows the ID mapping capability of Pathview.</p>

                <p><a class="btn btn-default" href="api_examples#example3" role="button">View details »</a></p>
            </div>
            <div class="col-md-6">
                <div style="height:70px"><h2 style="text-align:left">Integrated Pathway Analysis </h2></div>

                <p>This example covers an integration pathway analysis workflow based on Pathview.</p>

                <p><a class="btn btn-default" href="api_examples#example4" role="button">View details »</a></p>
            </div>
        </div>
<div class="col-md-12"></div>
<div class="col-md-12 content">


<div>
<h1 class="arg_content">Options for the API (see GUI option page for details)</h1>
<div>
<section id="options">
   <table>
     <tr>
       <th width="20%">Flag</th>
       <th width="50%" >Description</th>
       <th width="15%">Default(Common)</th>
       <th width="15%">GUI Options</th>
     </tr>
     <tr>
       <td colspan="4" style="text-align: center;"><b>Input/Output</b></td>
     </tr>
     <tr>
        <td> --gene_data</td>
        <td> Gene Data accepts data matrices in tab- or comma-delimited format (txt or csv).</td>
        <td> (/home/data/gse16873.d3.txt)</td>
        <td><a href="tutorial#gene_data"  
             onclick="window.open('tutorial#gene_data', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
   	  target="_blank" >Gene Data</a></td>
     </tr>
     <tr>
        <td>--cpd_data</td>
        <td>Compound Data accepts data matrices in tab- or comma-delimited format (txt or csv).</td>
        <td>(/home/data/sim.cpd.data2.txt)</td>
        <td><a href="tutorial#cpd_data" 
             onclick="window.open('tutorial#cpd_data', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Compound Data</a></td>
     </tr>
     <tr>
        <td>--gene_reference</td>
        <td>The column numbers for controls. Not needed if data is relative abundance (log ratios or fold changes).</td>
        <td>NULL(1,3,5)</td>
        <td><a href="tutorial#gene_data" 
             onclick="window.open('tutorial#gene_data', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Control/reference</a></td>
     </tr>
     <tr>
        <td>--gene_sample</td>
        <td>The column numbers for cases. Not needed if data is relative abundance (log ratios or fold changes).</td>
        <td>NULL(2,4,6)</td>
        <td><a href="tutorial#gene_data"
             onclick="window.open('tutorial#gene_data', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Case/sample</a></td>
     </tr>
     <tr>
        <td>--gene_compare</td>
        <td>Whether the experiment samples are paired or not. Not needed if data is relative abundance (log ratios or fold changes).</td>
        <td>paired</td>
        <td><a href="tutorial#gene_data"
             onclick="window.open('tutorial#gene_data', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Compare</a></td>
     </tr>
     <tr>
        <td>--cpd_reference</td>
        <td>The column numbers for controls. Not needed if data is relative abundance (log ratios or fold changes).</td>
        <td>NULL(1,3,5)</td>
        <td><a href="tutorial#cpd_data"
             onclick="window.open('tutorial#cpd_data', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Control/reference</a></td>
     </tr>
     <tr>
        <td>--cpd_sample</td>
        <td>The column numbers for cases. Not needed if data is relative abundance (log ratios or fold changes).</td>
        <td>NULL(2,4,6)</td>
        <td><a href="tutorial#cpd_data"
             onclick="window.open('tutorial#cpd_data', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Case/sample</a></td>
     </tr>
     <tr>
        <td>--cpd_compare</td>
        <td>Whether the experiment samples are paired or not. Not needed if data is relative abundance (log ratios or fold changes).</td>
        <td>paired</td>
        <td><a href="tutorial#cpd_data"
             onclick="window.open('tutorial#cpd_data', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Compare</a></td>
     </tr>
     <tr>
        <td>--suffix</td>
        <td>The suffix to be added after the pathway name as part of the output graph file name.</td>
        <td>pathview</td>
        <td><a href="tutorial#suffix"
             onclick="window.open('tutorial#suffix', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Output Suffix</a></td>
     </tr>
     <tr>
        <td>--auto_sel</td>
        <td> Whether to select pathways manually or through pathway analysis. If set to T (or TRUE), --pathway_id option will be ignored.</td>
        <td>F</td>
        <td><a href="tutorial#auto_sel"
             onclick="window.open('tutorial#auto_sel', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Pathway Selection</a></td>
     </tr>
     <tr>
        <td>--pathway_id</td>
        <td> The KEGG pathway IDs, usually 5 digit long.Pathway IDs can also be provided in a comma separated file.This option is not             needed when --auto_sel is T (or TRUE).</td>
        <td>00010(00010,00640)</td>
        <td><a href="tutorial#pwy_id"
             onclick="window.open('tutorial#pwy_id', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Pathway ID</a></td>
     </tr>
     <tr>
        <td>--gene_id</td>
        <td>ID type used for the Gene Data.</td>
        <td>ENTREZ</td>
        <td><a href="tutorial#gene_id"
             onclick="window.open('tutorial#gene_id', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Gene ID Type</a></td>
     </tr>
     <tr>
        <td>--cpd_id</td>
        <td>ID type used for the Compound Data.</td>
        <td>KEGG</td>
        <td><a href="tutorial#cpd_id"
             onclick="window.open('tutorial#cpd_id', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Compound ID Type</a></td>
     </tr>
     <tr>
        <td>--species</td>
        <td>Either the KEGG code, scientific name or the common name of the target species. <a href="/analysis"i>Click</a> GUI dropdown for Species if not sure.</td>
        <td>hsa</td>
        <td><a href="tutorial#species"
             onclick="window.open('tutorial#species', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Species</a></td>
     </tr>
     <tr>
       <td colspan="4" style="text-align: center;"><b>Graphics</b></td>
     </tr>
     <tr>
        <td>--kegg</td>
        <td> Whether to render the pathway as native KEGG graph (.png) or using Graphviz layout engine (.pdf).</td>
        <td>T</td>
        <td><a href="tutorial#kegg"
             onclick="window.open('tutorial#kegg', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Kegg Native</a></td>
     </tr>
     <tr>
        <td>--layer</td>
        <td> Controls plotting layers. Check the GUI option page for details.</td>
        <td>F</td>
        <td><a href="tutorial#layer"
             onclick="window.open('tutorial#layer', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Same Layer</a></td>
     </tr>
     <tr>
        <td>--split</td>
        <td> Whether split node groups are split to individual nodes.</td>
        <td>F</td>
        <td><a href="tutorial#split"
             onclick="window.open('tutorial#split', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Split Group</a></td>
     </tr>
     <tr>
        <td>--expand</td>
        <td> Whether the multiple-gene nodes are expanded into single-gene nodes.</td>
        <td>F</td>
        <td><a href="tutorial#expand"
             onclick="window.open('tutorial#expand', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Expand Node</a></td>
     </tr>
     <tr>
        <td>--multistate</td>
        <td> Whether multiple states (samples or columns) Gene Data or Compound Data should be integrated and plotted in the same graph.</td>
        <td>T</td>
        <td><a href="tutorial#multi"
             onclick="window.open('tutorial#multi', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Multi State</a></td>
     </tr>
     <tr>
        <td>--matched</td>
        <td> Whether the samples of Gene Data and Compound Data are paired.</td>
        <td>T</td>
        <td><a href="tutorial#match"
             onclick="window.open('tutorial#match', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Match Data</a></td>
     </tr>
     <tr>
        <td>--discrete_gene</td>
        <td>Whether Gene Data should be treated as discrete.</td>
        <td>F</td>
        <td><a href="tutorial#desc"
             onclick="window.open('tutorial#desc', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Discrete Gene</a></td>
     </tr>
     <tr>
        <td>--discrete_cpd</td>
        <td>Whether Compound Data should be treated as discrete.</td>
        <td>F</td>
        <td><a href="tutorial#desc"
             onclick="window.open('tutorial#desc', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Discrete Compound</a></td>
     </tr>
     <tr>
        <td>--keyposition</td>
        <td>Controls the position of color key(s).</td>
        <td>topleft</td>
        <td><a href="tutorial#kpos"
             onclick="window.open('tutorial#kpos', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Key Position</a></td>
     </tr>
     <tr>
        <td>--signatureposition</td>
        <td>Controls the position of pathview signature.</td>
        <td>bottomright</td>
        <td><a href="tutorial#spos"
             onclick="window.open('tutorial#spos', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Signature Position</a></td>
     </tr>
     <tr>
        <td>--offset</td>
        <td>How much compound labels should be put above the default position or node center.</td>
        <td>1.0</td>
        <td><a href="tutorial#clabel"
             onclick="window.open('tutorial#clabel', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Compound Label Offset</a></td>
     </tr>
     <tr>
        <td>--align</td>
        <td>How the color keys are aligned when both Gene Data and Compound Data are present. Potential values are x and y.</td>
        <td>x</td>
        <td><a href="tutorial#kalign"
             onclick="window.open('tutorial#kalign', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Keys Alignment</a></td>
     </tr>
     <tr>
       <td colspan="4" style="text-align: center;"><b>Coloration</b></td>
     </tr>
     <tr>
        <td>--limit_gene</td>
        <td>The limit values for Gene Data when converting them to pseudo colors.</td>
        <td>1(-1,1)</td>
        <td><a href="tutorial#limit"
             onclick="window.open('tutorial#limit', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Limit Gene</a></td>
     </tr>
     <tr>
        <td>--limit_cpd</td>
        <td>The limit values for Compound Data when converting them to pseudo colors.</td>
        <td>1(-1,1)</td>
        <td><a href="tutorial#limit"
             onclick="window.open('tutorial#limit', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Limit Compound</a></td>
     </tr>
     </tr>
     <tr>
        <td>--bins_gene</td>
        <td> The number of levels or bins for Gene Data when converting them to pseudo colors.</td>
        <td>10</td>
        <td><a href="tutorial#bins"
             onclick="window.open('tutorial#bins', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Bins Gene</a></td>
     </tr>
     <tr>
        <td>--bins_cpd</td>
        <td> The number of levels or bins for Compound Data when converting them to pseudo colors.</td>
        <td>10</td>
        <td><a href="tutorial#bins"
             onclick="window.open('tutorial#bins', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Bins Compound</a></td>
     </tr>
     <tr>
        <td>--node_sum</td>
        <td>The method name to calculate node summary  given that multiple genes or compounds are mapped to it. Potential values can be found in Node Sum drop down list in GUI.</td>
        <td>sum(mean)</td>
        <td><a href="tutorial#nsum"
             onclick="window.open('tutorial#nsum', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Node Sum</a></td>
     </tr>
     <tr>
        <td>--na_color</td>
        <td>  The color used for NA's or missing values in Gene Data and Compound Data. Potential value can be transparent or grey.</td>
        <td>transparent</td>
        <td><a href="tutorial#ncolor"
             onclick="window.open('tutorial#ncolor', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">NA Color</a></td>
     </tr>
     <tr>
        <td>--low_gene /--mid_gene / --high_gene </td>
        <td> The color spectrum to code Gene Data.Hex color codes can also be given (#00FF00, #D3D3D3).</td>
        <td>green/gray/red</td>
        <td><a href="tutorial#color"
             onclick="window.open('tutorial#color', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Low,Mid,High Gene</a></td>
     </tr>
     <tr>
        <td> --low_cpd / --mid_cpd / --high_cpd</td>
        <td> The color spectrum to code Compound Data.Hex color codes can also be given (#00FF00, #D3D3D3).</td>
        <td>blue/gray/yellow</td>
        <td><a href="tutorial#color"
             onclick="window.open('tutorial#color', 'newwindow', 'width=500, height=500,status=1,scrollbars=1').focus(); return false;"        
             target="_blank">Low,Mid,High Compound</a></td>
     </tr>
     <tr>
       <td colspan="4" style="text-align: center;"><b>API Specific</b></td>
     </tr>
        <td> --username </td>
        <td> The registered email to access the API.</td>
        <td> guest </td>
        <td> NA </td>
     <tr>
     </tr>
        <td> --password </td>
        <td> The password for the registered account to access the API.</td>
        <td> NULL </td>
        <td> NA </td>
     <tr>
    </tr>
   </table>
   </section>
 </div>
</div>
       <h1 class="arg_content">Output from API</h1>
        <p>If API query succeeds, it will generate a url in json format for results download.</p>
        <p> Example: {"download link":"http://pathview.uncc.edu//all/demo/57b61a93e1fab/file.zip"} </p>
        <p> If any problem occurs, the API will throw error messages or warnings, which are usually self-explanatory. </p>

       <h1 class="arg_content">Implementation</h1>
         <p>
         Pathview Web API is RESTful as it adheres to the REST (Representational State Transfer) architecture. In particular, this API (1) is stateless, (2) use base URLs to its resources (3) supports standard HTTP methods (e.g. GET, POST, etc) , and (4) returns its responses in JSON format. Pathview Web API currently supports HTTP queries through POST but not GET. While GET method is often used directly in a web browser for data retrieval (in URL format), POST method allows more complex queries with user input data and options (through command-line like statements). Pathview Web API and GUI have different front ends (command line vs web page) and response formats (JSON vs HTML), but they share the same backend. The key difference between here is that they use different Controllers in the Laravel MVC (Model-View-Controller) framework (details at https://laravel.com).
         </p>
         
                <h1 class="arg_content">Reference</h1>
                <p class="content1"><i>Luo W, Brouwer C. Pathview: an R/Biocondutor package for
                        pathway-based data integration and visualization. Bioinformatics, 2013,
                        29(14):1830-1831, doi:
                        <a href="http://bioinformatics.oxfordjournals.org/content/29/14/1830.full" target="_blank">
                            <u>10.1093/bioinformatics/btt285</u></a>
                    </i></p>

                <h1 class="arg_content">Contact</h1>

                <p class="contact">Email us: <a href="mailto:pathomics@gmail.com">pathomics@gmail.com</a></p>

    </div>
    <div class="scroll">
        <a href="#" class="scrollToTop"><span class="glyphicon glyphicon-menu-up"
                                              style="font-size: 30px; margin-left: 100px;"></span></a></div>
  </div>
</div>
</div>
    <style>
        .scrollToTop {
            width: 100px;
            height: 130px;
            padding: 10px;
            text-align: center;
            background: whiteSmoke;
            font-weight: bold;
            color: #444;
            text-decoration: none;
            position: fixed;
            top: 75px;
            right: 40px;
            display: none;
            font-size: 50px;

            background: url("/images/icontop.png") no-repeat 0px 20px;
        }

        .scrollToTop:hover {
            text-decoration: none;
        }
    </style>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
    <script>
        $(document).ready(function () {

            //Check to see if the window is top if not then display button
            $(window).scroll(function () {
                if ($(this).scrollTop() > 100) {
                    $('.scrollToTop').fadeIn();
                } else {
                    $('.scrollToTop').fadeOut();
                }
            });

            //Click event to scroll to top
            $('.scrollToTop').click(function () {
                $('html, body').animate({scrollTop: 0}, 800);
                return false;
            });

        });
    </script>

@stop
