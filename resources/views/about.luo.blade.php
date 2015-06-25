@extends('app')

@section('content')
    <div class="content">
    <div class="rows">
        <div class="col-sm-12">
            <div class="col-sm-8 corousel-content ">
                <h2 class="homepage-heading">Introduction</h2>

                <p class="content1">
                    <a href="http://www.bioconductor.org/packages/release/bioc/html/pathview.html" target="_blank">Pathview</a> maps, integrates and renders a wide variety of biological data on relevant pathway graphs. Here is a detailed <a href="all/demo/example/pathviewIntro_flyer.pdf" target="_balnk">overview</a>.
                    Pathview Web provides easy interactive access, and generates high quality, hyperlinked graphs. Pathview package is written in R/Bioconductor, this web interface is built on PHP with XAMPP and R.
                </p>

                <h2 class="homepage-heading">Credits</h2>

                <p class="content1">
                    Pathview web is designed and developed by Weijun Luo and Yeshvant Kumar Bhavnasi. We also get support from Steven Blanchard, Cory Brouwer and the whole Bioinformatics Service Division (BSD) at UNC Charlotte. 
                </p>
                <p class="content1">
                    This project is sponsored by BSD and Department of Bioinformatics and Genomics, and is supported by the Faculty Innovaton Fund to Weijun Luo from the College of Computing and Informatics.
                </p>

                <h2 class="homepage-heading">Disclaimer</h2>

                <p class="content1">
                    Pathview is an open source software package distributed under <a href="http://www.gnu.org/licenses/gpl-3.0.html" target="_blank">GPLv3</a>. Pathview downloads and uses KEGG data. Academic users may freely use the KEGG website, but other uses may require a license agreement (details at <a href="http://www.kegg.jp/kegg/legal.html" target="_blank">KEGG website</a>).
                </p>

                                <h2 class="homepage-heading">Citations:</h2>
                                    <p class="content1"><i>
                                        Luo W, Brouwer C. Pathview: an R/Biocondutor package for pathway-based data integration and visualization. Bioinformatics, 2013, 29(14):1830-1831, doi:
                                        <a href="http://bioinformatics.oxfordjournals.org/content/29/14/1830.full" target="_blank">10.1093/bioinformatics/btt285</a>
                                    </i></p>
                                    <p class="content1">
                                    </p>
				    <br>
                                    <p class="content1">
                                        Please cite our paper if you use the Pathview package or this website. In addition, please cite this website if you use it. This will help the Pathview project in return.
                                    </p>

            </div>

            <div class="col-sm-4 leftsidebar">

                    <?php
                    if(Auth::user())
                    {
                        ?>
                        <a href="/analysis">
                        <button type="button"  class="btn btn-primary btn-lg GetStarted " >
                            Quick Start
                        </button>
                            </a>
                    <?php
                    }
                        else{?>
                        <a href="/guest">
                    <button type="button"  class="btn btn-primary btn-lg GetStarted " >
                        Quick Start
                    </button>
                        </a>
                    <?php }?>

                        <div class="panel panel-default">
                            <div class="panel-heading leftpanel" >Usage Statistics</div>
                            <div class="panel-body">
                                <p >Pathview web</p>

                                <div id="graph" class="col-md-12">


                                    <table class="tables" >
                                        <tr>

                                            <td>
                                                <div class="bar1" >
                                                    &nbsp&nbsp
                                                </div>
                                            </td>
                                            <td class="tdContent">Graphs:</td>
                                            <td><?php echo $web_dnld_cnt ?></td>
                                            <td>
                                                <div class="bar2">
                                                    &nbsp&nbsp
                                                </div>
                                            </td>
                                            <td  class="tdContent">IP's:</td>
                                            <td><?php echo $web_ip_cnt ?></td>
                                        </tr>

                                    </table>
                                </div>
                                <canvas id="myChart" ></canvas>
                                <p> Bioc Package</p>
                                <div id="graph1" class="col-md-12">
                                    <table class="tables">
                                        <tr>
                                            <td>
                                                <div class="bar1" >
                                                    &nbsp&nbsp
                                                </div>
                                            </td>

                                            <td  class="tdContent">Downloads:</td>

                                            <td>&#8776;<?php echo $bioc_dnld_cnt ?></td>

                                            <td>
                                                <div class="bar2">
                                                    &nbsp&nbsp
                                                </div>
                                            </td>

                                            <td  class="tdContent">IP's:</td>

                                            <td>&#8776;<?php echo $bioc_ip_cnt ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <canvas id="myChart1" ></canvas>
                            </div>
                        </div>
        </div>


    </div>
    </div>
    </div>


@stop