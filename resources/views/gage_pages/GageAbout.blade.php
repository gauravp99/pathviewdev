@extends('GageApp')

@section('content')
    <div class="content">
        <div class="rows">
            <div class="col-sm-12">
                <div class="col-sm-8 corousel-content ">
                    <h2 class="homepage-heading">Introduction</h2>

                    <p class="content1">
                        <a href="http://www.bioconductor.org/packages/release/bioc/html/gage.html" target="_blank">GAGE</a> is an established method for gene set (enrichment or GSEA) or pathway analysis.
                        GAGE is generally applicable independent of microarray or RNA-Seq data attributes including sample sizes,
                        experimental designs, assay platforms, and other types of heterogeneity,
                        and consistently achieves superior performance over other frequently used methods.
                        For more information, please check the <a href="data/pathviewIntro_flyer.pdf" target="_balnk">paper</a>.
                            <br/>
                            GAGE Web provides easy interactive access to GAGE analysis. It features:
                            <ol style="margin-left:30px;">
                                <li>A complete pathway analysis workflow based on GAGE and Pathview</li>
                                <li>Support to >3000 species, dozens of molecular IDs, various omics data and gene-set data (KEGG pathways, Gene Ontology, SMPDB etc);</li>
                                <li>Over-representation test on preselected gene or molecule lists i.e. the discrete version of pathway analysis.</li>
                            </ol>

                    </p>

                    <h2 class="homepage-heading">Credits</h2>

                    <p class="content1">
                        Gage and Pathview web is designed and developed by Weijun Luo and Yeshvant Kumar Bhavnasi. We also get
                        support from Steven Blanchard, Cory Brouwer and the whole <a href="https://bioservices.uncc.edu/" target="_blank">Bioinformatics Service Division (BiSD)</a>
                        at UNC Charlotte.
                    </p>





                    <h2 class="homepage-heading">Disclaimer</h2>

                    <p class="content1">
                        Gage and Pathview is an open source software package distributed under <a
                                href="http://www.gnu.org/licenses/gpl-3.0.html" target="_blank">GPLv3</a>. Pathview
                        downloads and uses KEGG data. Academic users may freely use the KEGG website, but other uses may
                        require a license agreement (details at <a href="http://www.kegg.jp/kegg/legal.html"
                                                                   target="_blank">KEGG website</a>).
                    </p>

                    <h2 class="homepage-heading">Citations:</h2>

                    <p class="content1"><i>
                            Luo W, Friedman M, etc. GAGE: generally applicable gene set enrichment for pathway analysis. BMC Bioinformatics, 2009, 10, pp. 161, doi: 10.1186/1471-2105-10-161
                            <br/>
                            Luo W, Brouwer C. Pathview: an R/Biocondutor package for pathway-based data integration and visualization. Bioinformatics, 2013, 29(14):1830-1831, doi: 10.1093/bioinformatics/btt285
                        </i></p>

                    <p class="content1">
                    </p>
                    <br>

                    <p class="content1">
                        Please cite the GAGE paper and pathview.uncc.edu if you use this website. In addition, please cite the Pathview paper if you the Pathview visualization here.
                        This will help the GAGE and Pathview projects in return.
                    </p>

                    <h2 class="homepage-heading">Sponsors</h2>

                    <p class="content1">
                        This project is sponsored by <a href="https://bioservices.uncc.edu/" target="_blank">BiSD</a> and <a href="http://bioinformatics.uncc.edu/" target="_blank">Department of Bioinformatics and Genomics</a>, and is supported
                        by the Faculty Innovaton Fund to Weijun Luo from the <a href="http://cci.uncc.edu/" target="_blank">College of Computing and Informatics</a>.
                    </p>
                    <div class="col-sm-12">
                        <div class="col-sm-3">

                        </div>
                        <div class="col-sm-6">
                            <a href="https://bioservices.uncc.edu/" target="_blank"><img src="images/servicesDivisionLogo.png" width="45%" height="45%"></a>
                            <a href="http://bioinformatics.uncc.edu/" target="_blank"><img src="images/BioinformaticsLogo.jpg" width="50%" height="50%"></a>
                        </div>
                        <div class="col-sm-3">

                        </div>

                    </div>

                </div>
                <div class="col-sm-4">
                    <a href="/gage">
                        <button type="button"  class="btn btn-primary btn-lg GetStarted" style="margin-top:20px;" >
                            Quick Start
                        </button>
                    </a>
                    <a href="/analysis">
                        <button type="button"  class="btn btn-primary btn-lg GetStarted" style="margin-top:50px;">
                            Try Pathview
                        </button>
                    </a>
                    <div class="panel panel-default">
                        <div class="panel-heading leftpanel">Usage Statistics</div>
                        <div class="panel-body">
                            <p>Gage web</p>

                            <div id="graph1" class="col-md-12">


                                <table class="tables">
                                    <tr>

                                        <td>
                                            <div class="bar12">
                                                &nbsp
                                            </div>
                                        </td>
                                        <td class="tdContent">Gage Analysis:</td>
                                        <td><?php echo $web_dnld_cnt ?></td>
                                        <td>
                                            <div class="bar11">
                                                &nbsp
                                            </div>
                                        </td>
                                        <td class="tdContent">IP's:</td>
                                        <td><?php echo $web_ip_cnt ?></td>
                                    </tr>

                                </table>
                            </div>
                            <canvas id="myChart"></canvas>
                            <p> Bioc Package</p>

                            <div id="graph1" class="col-md-12">
                                <table class="tables">
                                    <tr>
                                        <td>
                                            <div class="bar2">
                                                &nbsp&nbsp
                                            </div>
                                        </td>

                                        <td class="tdContent">Downloads:</td>
                                        <td>&#8776;<?php echo $bioc_dnld_cnt ?></td>


                                        <td>
                                            <div class="bar1">
                                                &nbsp&nbsp
                                            </div>
                                        </td>

                                        <td class="tdContent">IP's:</td>
                                        <td>&#8776;<?php echo $bioc_ip_cnt ?></td>

                                    </tr>
                                </table>
                            </div>
                            <canvas id="myChart1"></canvas>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

@stop