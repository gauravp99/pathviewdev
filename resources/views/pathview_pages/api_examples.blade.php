@extends('app')
@section('content')
        <div class="col-sm-12">

            <section id="example1">


                <h1 class="arg_content">Example 1: Multiple Sample KEGG View</h1>

                <p>
                    This example shows the multiple sample/state integration with Pathview KEGG vieww. Data files are
                    pre
                    loaded and the options have been preset as below.
                    Data files used in this example are <a href="data/gse16873.d3.txt" target="_balnk">Gene
                        Data</a> and <a href="data/sim.cpd.data2.csv" target="_balnk">Compound Data</a>.
                    In this example Gene Data has 3 samples and Compound Data has 2 samples.
                <p> API Invocation </p> 
                <p style="font-size: 15px; margin-left: 50px;">
                    ./pathwayapi.sh  --gene_data gse16873.d3.txt --cpd_data sim.cpd.data2.csv --species hsa --gene_id ENTREZ --cpd_id KEGG --pathway_id 00640 --suffix multistatekegg   </p>
                </p>
                </br>

                <div class="col-sm-8">

                    <img src="data/hsa00640.multistate.kegg.multi.png" style="width: 99%;">
                </div>
                <div class="col-sm-4">
                    <ul class="list-group">
                        <li class="list-group-item disabled">GUI Arguments</li>
                        <li class="list-group-item">
                            <div class="pathviewargVar">Pathway ID</div>
                            <div class="pathviewargColon">:</div>
                            <div class="pathviewargVal"> 00640-Propanoate metabolism</div>
                        </li>
                        <li class="list-group-item">
                            <div class="pathviewargVar">Species</div>
                            <div class="pathviewargColon">:</div>
                            <div class="pathviewargVal">hsa</div>
                        </li>
                        <li class="list-group-item">
                            <div class="pathviewargVar">Kegg Native</div>
                            <div class="pathviewargColon">:</div>
                            <div class="pathviewargVal">TRUE</div>
                        </li>
                        <li class="list-group-item">
                            <div class="pathviewargVar">Same Layer</div>
                            <div class="pathviewargColon">:</div>
                            <div class="pathviewargVal">TRUE</div>
                        </li>
                        <li class="list-group-item">
                            <div class="pathviewargVar">Multi State</div>
                            <div class="pathviewargColon">:</div>
                            <div class="pathviewargVal">TRUE</div>
                        </li>
                        <li class="list-group-item">
                            <div class="pathviewargVar">Match Data</div>
                            <div class="pathviewargColon">:</div>
                            <div class="pathviewargVal">FALSE</div>
                        </li>
                    </ul>
                    <a href="/example1">
                        <button type="button" class="btn btn-primary btn-lg GetStarted  " data-toggle="modal">
                            Try It
                        </button>
                    </a>
                </div>
            </section>

        </div>

        <div class="col-sm-12">

            <section id="example2">
                <h1 class="arg_content">Example 2: Multiple Sample Graphviz View</h1>

                <p>
                    This example shows the multiple sample/state integration with Pathview Graphviz viewo. Data files
                    are pre
                    loaded and the options have been preset as below.
                    Data files used in this example are <a href="data/gse16873.d3.txt" target="_blank">Gene
                        Data</a> and <a href="data/sim.cpd.data2.csv" target="_blank">Compound Data</a>.
                    In this example Gene Data has 3 samples and Compound Data has 2 samples. Difference on this example
                    is
                    you are generating a Graphviz view
                    by unchecking the kegg view selection box.
                <p> API Invocation </p>
		<p style="font-size: 15px; margin-left: 50px;">
		./pathwayapi.sh --gene_data gse16873.3.txt --cpd_data sim.cpd.data1.csv --species hsa --pathway_id 00640 --suffix multi --kegg F  --limit_gene -1,2  --cpd_reference 1,2 --cpd_sample 3,4 --gene_reference 1,3,5 --gene_sample 2,4,6 </p>
              
                </p>
                </br>

                <div class="col-sm-8">
                    <img src="data/file-page1.png" style="width: 99%;">

                </div>
                <div class="col-sm-4">
                    <ul class="list-group">
                        <a class="list-group-item disabled">
                            GUI Arguments
                        </a>
                        <li class="list-group-item">
                            <div class="pathviewargVar">Pathway ID</div>
                            <div class="pathviewargColon">:</div>
                            <div class="pathviewargVal">00640-Propanoate metabolism</div>
                        </li>
                        <li class="list-group-item">
                            <div class="pathviewargVar">Species</div>
                            <div class="pathviewargColon">:</div>
                            <div class="pathviewargVal">hsa</div>
                        </li>
                        <li class="list-group-item">
                            <div class="pathviewargVar">Kegg Native</div>
                            <div class="pathviewargColon">:</div>
                            <div class="pathviewargVal">FALSE</div>
                        </li>
                        <li class="list-group-item">
                            <div class="pathviewargVar">Same Layer</div>
                            <div class="pathviewargColon">:</div>
                            <div class="pathviewargVal">TRUE</div>
                        </li>
                        <li class="list-group-item">
                            <div class="pathviewargVar">Multi State</div>
                            <div class="pathviewargColon">:</div>
                            <div class="pathviewargVal">TRUE</div>
                        </li>
                        <li class="list-group-item">
                            <div class="pathviewargVar">Match Data</div>
                            <div class="pathviewargColon">:</div>
                            <div class="pathviewargVal">FALSE</div>
                        </li>
                        <li class="list-group-item">
                            <div class="pathviewargVar">Gene Limit</div>
                            <div class="pathviewargColon">:</div>
                            <div class="pathviewargVal">-1(min),2(max)</div>
                        </li>

                    </ul>
                    <a href="/example2">
                        <button type="button" class="btn btn-primary btn-lg GetStarted  " data-toggle="modal">
                            Try it
                        </button>
                    </a>
                </div>
            </section>

        </div>

        <div class="col-sm-12">

            <section id="example3">

                <h1 class="arg_content">Example 3: ID Mapping</h1>

                <p>
                    Here's an example showing the ID mapping capability of pathview. Data files are pre loaded and the
                    options have been preset as below (including Gene ID type and Compound ID type).
                    Data files used in this example are <a href="data/gene.ensprot.txt"
                                                           target="_blank">Gene
                        Data</a> and <a href="data/cpd.cas.csv" target="_blank">Compound Data</a>.

                </p>
                <p>  API Invocation </p>
		<p style="font-size: 15px; margin-left: 50px;">
		./pathwayapi.sh  --gene_data gene.ensprot.txt  --cpd_data cpd.cas.csv  --species hsa --gene_id ENSEMBLPROT --cpd_id 'CAS Registry Number' --pathway_id 00640 --suffix IDMapping --limit_gene 3 --limit_cpd 3 --bins_gene 6 --bins_cpd 6 </p>

                <div class="col-sm-8">
                    <img src="data/hsa00640.IDMapping.png" style="width: 99%;">
                </div>
                <div class="col-sm-4">
                    <ul class="list-group">
                        <a class="list-group-item disabled">
                            GUI Arguments
                        </a>
                        <li class="list-group-item">
                            <div class="pathviewargVar">Pathway ID</div>
                            <div class="pathviewargColon">:</div>
                            <div class="pathviewargVal">00640-Propanoate metabolism</div>
                        </li>
                        <li class="list-group-item">
                            <div class="pathviewargVar">Species</div>
                            <div class="pathviewargColon">:</div>
                            <div class="pathviewargVal">hsa</div>
                        </li>
                        <li class="list-group-item">
                            <div class="pathviewargVar">Gene ID</div>
                            <div class="pathviewargColon">:</div>
                            <div class="pathviewargVal">ENSEMBLPROT</div>
                        </li>
                        <li class="list-group-item">
                            <div class="pathviewargVar">Compound ID</div>
                            <div class="pathviewargColon">:</div>
                            <div class="pathviewargVal">CAS Registry Number</div>
                        </li>
                        <li class="list-group-item">
                            <div class="pathviewargVar">Kegg Native</div>
                            <div class="pathviewargColon">:</div>
                            <div class="pathviewargVal">TRUE</div>
                        </li>
                        <li class="list-group-item">
                            <div class="pathviewargVar">Same Layer</div>
                            <div class="pathviewargColon">:</div>
                            <div class="pathviewargVal">TRUE</div>
                        </li>
                        <li class="list-group-item">
                            <div class="pathviewargVar">Limit</div>
                            <div class="pathviewargColon">:</div>
                            <div class="pathviewargVal">Gene:3; Compound: 3</div>
                        </li>
                        <li class="list-group-item">
                            <div class="pathviewargVar">Bins</div>
                            <div class="pathviewargColon">:</div>
                            <div class="pathviewargVal">Gene:6; Compound: 6</div>
                        </li>

                    </ul>
                    <a href="/example3">
                        <button type="button" class="btn btn-primary btn-lg GetStarted  " data-toggle="modal">
                            Try It
                        </button>
                    </a>
                </div>
            </section>

        </div>
@stop
