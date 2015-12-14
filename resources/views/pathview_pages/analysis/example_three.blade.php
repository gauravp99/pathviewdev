
@extends('app')

@section('content')

    @include('navigation')
    <script src="js/sliding.form.js"></script>
    <div class="col-sm-9">
        <div class="conetent-header">


    <p><b>Example Analysis 3: ID Mapping</b></p>


        </div>
        <div class="col-sm-5">

            @if ($errors->any())
                <ul class="alert alert-danger" style="font-size: 14px;list-style-type: none;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif


            @if(Session::get('err')!=NULL)

                <ul class="alert alert-danger" style="font-size: 14px;list-style-type: none;">
                    <?php
                    for ($x = 0; $x < sizeof(Session::get('err')); $x++) {
                        echo "<li>" . Session::get('err')[$x] . "</li>";
                    }
                    ?>
                </ul>

            @endif
            <ul id="errors" hidden="true" class="alert alert-danger" style="font-size: 14px;list-style-type: none;">

            </ul>
        </div>
    </div>

    <?php
            /** Default values for all the fields of the pathview attributes **/
    $gfile = "gene.ensprot.txt";
    $cpdfile = "cpd.cas.csv";
    $geneid = "ENSEMBLPROT";
    $cpdid = "CAS Registry Number";
    $species = "hsa-Homo sapiens-human";
    $pathway = "00010-Glycolysis / Gluconeogenesis";
    $selectpath = "00640-Propanoate metabolism,";
    $pathidx = 0;
    $suffix = "IDMapping";
    $kegg = true;
    $layer = true;
    $split = false;
    $expand = false;
    $multistate = true;
    $matchd = true;
    $gdisc = false;
    $cdisc = false;
    $kpos = "topright";
    $pos = "bottomleft";
    $offset = "1.0";
    $align = "y";
    $glmt = 3;
    $gbins = 6;
    $glow = "green";
    $gmid = "grey";
    $ghigh = "red";
    $clmt = 3;
    $cbins = 6;
    $clow = "blue";
    $cmid = "grey";
    $chigh = "yellow";
    $nsum = "sum";
    $ncolor = "transparent";
    ?>

    {{--Div for Form for generating the analysis Form is devided into pages
    1. Input and Output
    2. Graphics
    3. Coloration --}}
    <div class="col-sm-7">
        <div id="content">
            <div id="wrapper">
                <div id="navigation" style="display:none;">
                    <ul>
                        <li class="selected">
                            <a href="#" id="inputOutput">Input & Output</a>
                        </li>
                        <li>
                            <a href="#" id="graphics">Graphics</a>
                        </li>
                        <li>
                            <a href="#" id="coloration">Coloration</a>
                        </li>

                    </ul>
                </div>



                {{--Input and Output slider --}}
                <div id="steps">



                    {!! form::open(array('url' => 'post_exampleAnalysis3','method'=>'POST','files'=>true,'id' =>
                    'anal_form')) !!}

                    <fieldset class="step">
                        <input type="hidden" id="page_is_dirty" name="page_is_dirty" value="0"/>

                        <div class="stepsdiv" id="gfile-div" @if (isset(Session::get('err_atr')['gfile']))
                             style="background-color:#DA6666;" @endif>
                            <div class="col-sm-12">
                                <div class="col-sm-5">
                                    <a href="tutorial#gene_data" onclick="window.open('tutorial#gene_data', 'newwindow', 'width=500, height=500, status=1,scrollbars=1').focus(); return false;" title="Gene Data accepts data matrices in tab- or comma-delimited format (txt or csv)."
                                       target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">

                                        <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"> </span>
                                    </a>
                                    {!!form::label('gcheck','Gene Data:')!!}
                                </div>
                                <div class="col-sm-7">


                                    <input name="gcheck" id="gcheck" value="T" type="checkbox"
                                    <?php if (isset(Session::get('Sess')['gcheck'])) {
                                        echo "checked";
                                    } else {
                                        if (Session::get('Sess') == NULL) {
                                            echo "checked";
                                        }
                                    }?>>


                                    <a href="/data/{{$gfile}}" target="_blank">{{$gfile}}</a>

                                </div>
                            </div>
                        </div>
                        <br/>
                        <br/>

                        <div class="stepsdiv" id="cfile-div" @if (isset(Session::get('err_atr')['cfile']))
                             style="background-color:#DA6666;" @endif>
                            <div class="col-sm-12">
                                <div class="col-sm-5">
                                    <a href="tutorial#cpd_data" onclick="window.open('tutorial#cpd_data', 'newwindow', 'width=500, height=500, status=1,scrollbars=1').focus(); return false;"  title="Compound Data accepts data matrices in tab- or comma-delimited format (txt or csv)."
                                       target="_blank" class="scrollToTop" style="float:left;margin-right:5px;">
                                        <span class="glyphicon glyphicon-info-sign" style="margin-right: 20px;"></span></a>
                                    {!!form::label('cpdcheck','Compound Data:')!!}
                                </div>
                                <div class="col-sm-7">


                                    <input name="cpdcheck" id="cpdcheck" value="T" type="checkbox"
                                    <?php if (isset(Session::get('Sess')['cpdcheck'])) {
                                        echo "checked";
                                    } else {
                                        if (Session::get('Sess') == NULL) {
                                            echo "checked";
                                        }
                                    }?>>
                                    <a href="/all/demo/example/{{$cpdfile}}" target="_blank">{{$cpdfile}}</a>
                                </div>
                            </div>


                        </div>

                        <br/>
                        <br/>


                       @include('analysis')
        </div>


    </div>
    </div>

        <script type="text/javascript">

            var pathway_array = [];
            var species_array = [];
            var gene_array = [];
            var cmpd_array = [];
            <?php
              $pathway = Cache::remember('Pathway_id', 10, function()
                    {
                        return DB::table('pathway')->get(array('pathway_id'));
                    });
                    if (Cache::has('Pathway_id'))
                    {
                        $pathway = Cache::get('Pathway_id');
                    }
            ?>
            <?php
                $species = Cache::remember('Species_id', 10, function()
                    {
                        return DB::table('species')->get(array('species_id'));
                    });
                    if (Cache::has('Species_id'))
                    {
                        $species = Cache::get('Species_id');
                    }
            ?>
            <?php
             $gene = Cache::remember('gene_id', 10, function()
                    {
                        return DB::table('gene')->get(array('gene_id'));;
                    });
                    if (Cache::has('gene_id'))
                    {
                        $gene = Cache::get('gene_id');
                    }
             $gene = DB::table('gene')->get(array('gene_id'));?>
            <?php
            $cmpd = Cache::remember('compound_id', 10, function()
                    {
                        return DB::table('compoundID')->get(array('compound_id'));;
                    });
                    if (Cache::has('compound_id'))
                    {
                        $cmpd = Cache::get('compound_id');
                    }
                ?>
            pathway_array = <?php echo JSON_encode($pathway);?>;
            species_array = <?php echo JSON_encode($species);?>;
            gene_array = <?php echo JSON_encode($gene);?>;
            cmpd_array = <?php echo JSON_encode($cmpd);?>;

        </script>
        <script src="{{asset('/js/analysis.js')}}"></script>


@stop

