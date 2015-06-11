Hello {{$name}},

<br/><br/>

You have successfully generated the pathview from <b>{{$anal_type}}</b> option on <b>{{$time}}</b>
<br/>
Please click here to see the output generated <a href={{url('/anal_hist'.$content)}}>{{$anal_type}}</a>

<br/>

<br/>
<h1> Analysis Input arguments </h1>
<br/>
<div class="bs-example" data-example-id="condensed-table">
    <table border=1 style="width:70%" class="table table-condensed">
        <thead>
        <tr>
            <th>Argument Name</th>
            <th>Value</th>

            <tbody>


            <?php
            $arguments = array();

            $arguments = explode(",", $argument);
            $arguments = array_unique($arguments);
            foreach($arguments as $arg)
            {


            if(strpos($arg, ":"))
            {

            $arg1 = explode(':', $arg);
            if(sizeof($arg1) > 1 && strcmp($arg1[0], "targedir") != 0 && strcmp($arg1[0], "geneextension") != 0 )
            {
            switch ($arg1[0]) {
                case "geneid":
                    $arg1[0] = "Gene ID Type";
                    break;
                case "cpdid":
                    $arg1[0] = "Compound ID Type";
                    break;
                case "species":
                    $arg1[0] = "Species";
                    $val = DB::select(DB::raw("select concat(concat(species_id,\"-\"),species_desc) as spe from Species where species_id like '$arg1[1]' LIMIT 1"));

                    if (sizeof($val) > 0) {
                        $arg1[1] = $val[0]->spe;
                    }
                    break;
                case "suffix":
                    $arg1[0] = "Output Suffix";
                    break;
                case "pathway":
                    $arg1[0] = "Pathway ID";
                    $val = DB::select(DB::raw("select concat(concat(pathway_id,\"-\"),pathway_desc) as path from Pathway where pathway_id like '$arg1[1]' LIMIT 1"));
                    if (sizeof($val) > 0) {
                        $arg1[1] = $val[0]->path;
                    }
                    break;
                case "kegg":
                    $arg1[0] = "Kegg Native";
                    if ($arg1[1] == 'T') {
                        $arg1[1] = "True";
                    } else {
                        $arg1[1] = "False";
                    }
                    break;
                case "layer":
                    $arg1[0] = "Same Layer";
                    if ($arg1[1] == 'T') {
                        $arg1[1] = "True";
                    } else {
                        $arg1[1] = "False";
                    }
                    break;
                case "gdisc":
                    $arg1[0] = "Descrete Gene";
                    if ($arg1[1] == 'T') {
                        $arg1[1] = "True";
                    } else {
                        $arg1[1] = "False";
                    }
                    break;
                case "cdisc":
                    $arg1[0] = "Descrete Compound";
                    if ($arg1[1] == 'T') {
                        $arg1[1] = "True";
                    } else {
                        $arg1[1] = "False";
                    }
                    break;
                case "split":
                    $arg1[0] = "Split Node";
                    if ($arg1[1] == 'T') {
                        $arg1[1] = "True";
                    } else {
                        $arg1[1] = "False";
                    }
                    break;
                case "expand":
                    $arg1[0] = "Expand Node";
                    if ($arg1[1] == 'T') {
                        $arg1[1] = "True";
                    } else {
                        $arg1[1] = "False";
                    }
                    break;
                case "multistate":
                    $arg1[0] = "Multi State";
                    if ($arg1[1] == 'T') {
                        $arg1[1] = "True";
                    } else {
                        $arg1[1] = "False";
                    }
                    break;
                case "matchd":
                    $arg1[0] = "Match Data";
                    if ($arg1[1] == 'T') {
                        $arg1[1] = "True";
                    } else {
                        $arg1[1] = "False";
                    }
                    break;
                case "offset":
                    $arg1[0] = "Compound Label Offset";
                    break;
                case "kpos":
                    $arg1[0] = "Key Position";
                    break;
                case "pos":
                    $arg1[0] = "Signature Position";
                    break;
                case "align":
                    $arg1[0] = "Key Alignment";
                    if ($arg1[1] == 'x') {
                        $arg1[1] = "X - Axis";
                    } else {
                        $arg1[1] = "y - Axis";
                    }
                    break;
                case "glmt":
                    $arg1[0] = "Gene Limit";
                    break;
                case "clmt":
                    $arg1[0] = "Compound Limit";
                    break;
                case "gbins":
                    $arg1[0] = "Gene Bins";
                    break;
                case "cbins":
                    $arg1[0] = "Compound Bins";
                    break;
                case "clow":
                    $arg1[0] = "Compound Low";
                    break;
                case "cmid":
                    $arg1[0] = "Compound Mid";
                    break;
                case "chigh":
                    $arg1[0] = "Compound High";
                    break;
                case "glow":
                    $arg1[0] = "Gene Low";
                    break;
                case "gmid":
                    $arg1[0] = "Gene Mid";
                    break;
                case "ghigh":
                    $arg1[0] = "Gene High";
                    break;
                case "nsum":
                    $arg1[0] = "Node Sum";
                    break;
                case "ncolor":
                    $arg1[0] = "NA Color";
                    break;
                case 'filename':
                    $arg1[0] = "Gene Data";
                    break;
                case 'cfilename':
                    $arg1[0] = "Compound Data";
                    break;

            }

            ?>
        <tr>
            <td><b>{{$arg1[0]}}</b></td>
            <td style='background-color:{{$arg1[1]}}'>{{$arg1[1]}}</td>


        </tr>
        <?php
        }
        } }?>

        </tbody>
    </table>
</div>

<br/>
<br/>


<b>Pathway Team</b>
<br/>
Pathview Project
<br/>

<a href={{url('/')}}><img src="/images/logo.png"></a>

<br/>

{{url('/')}}

<br/>

Project Contact Mail ID: luo_weijun@yahoo.com

<br/>

Report Issues Mail ID: byeshvant@hotmail.com

<br/>
@include('footer')
</body>
</html>

