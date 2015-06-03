<?php namespace App\Http\Controllers;
/**
 * Created by PhpStorm.
 * User: ybhavnasi
 * Date: 5/28/15
 * Time: 9:21 AM
 */




class Analysis
{

    var $gfile;
    var $cfile;
    var $geneid;
    var $cpdid;
    var $species;
    var $pathway = array();
    var $suffix;
    var $kegg;
    var $layer;
    var $split;
    var $expand;
    var $multistate;
    var $matchd;
    var $gdisc;
    var $cdisc;
    var $kpos;
    var $pos;
    var $offset;
    var $align;
    var $glmt;
    var $gbins;
    var $glow;
    var $gmid;
    var $ghigh;
    var $clmt;
    var $cbins;
    var $clow;
    var $cmid;
    var $chigh;
    var $nodesum;
    var $nacolor;
    var $pathidx;
    var $targdir;
    var $err_atr = array();
    var $errors = array();

    function __construct($_POST, $anal_type)
    {

        if (isset($_POST["geneid"])) {
            $gene = $_POST["geneid"];
            $val = DB::select(DB::raw("select geneid  from gene where geneid = '$gene' LIMIT 1 "));

            if (sizeof($val) > 0) {
                $this->$geneid = $_POST["geneid"];
            } else {
                array_push($errors, "Entered Gene ID doesn't exist");
                $err_atr["geneid"] = 1;
            }
        }

        if (isset($_POST["geneid"])) {
            $cpdid = $_POST["cpdid"];
            $val = DB::select(DB::raw("select cmpdid  from compound where cmpdid = '$cpdid' LIMIT 1 "));
            if (sizeof($val) > 0) {
                $this->cpdid = str_replace(" ", "-", $val[0]->cmpdid);
            } else {
                array_push($errors, "Entered compound ID doesn't exist");
                $err_atr["cpdid"] = 1;
            }
        }

        $spe = substr($_POST["species"], 0, 3);
        $val = DB::select(DB::raw("select species_id from Species where species_id = '$spe' LIMIT 1"));

        if (sizeof($val) > 0) {
            $this->species = $val[0]->species_id;
        } else {
            array_push($errors, "Entered Species ID doesn't exist");
            $err_atr["species"] = 1;
        }

        $this->suffix = preg_replace("/[^A-Za-z0-9 ]/", '', $_POST["suffix"]);

        if (isset($_POST["kegg"]))
            $this->kegg = "T";
        else
            $this->kegg = "F";

        if (isset($_POST["layer"]))
            $this->layer = "T";
        else
            $this->layer = "F";

        if (isset($_POST["split"]))
            $this->split = "T";
        else
            $this->split = "F";

        if (isset($_POST["expand"]))
            $this->expand = "T";
        else
            $this->expand = "F";

        if (isset($_POST["multistate"]))
            $this->multistate = "T";
        else
            $this->multistate = "F";

        if (isset($_POST["matchd"]))
            $this->matchd = "T";
        else
            $this->matchd = "F";

        if (isset($_POST["gdisc"]))
            $this->gdisc = "T";
        else
            $this->gdisc = "F";

        if (isset($_POST["cdisc"]))
            $this->cdisc = "T";
        else
            $this->cdisc = "F";

        $this->kpos = $_POST["kpos"];
        $this->pos = $_POST["pos"];

        if (preg_match('/[a-z]+/', $_POST["offset"])) {
            array_push($errors, "offset should be Numeric");
            $err_atr["offset"] = 1;
        } else {
            $this->offset = $_POST["offset"];
        }

        $this->align = $_POST["align"];

        if ((($anal_type == "newAnalysis") && isset($_POST["gfile"])) || isset($_POST["gcheck"])) {




        }



    }

    function __toString()
    {

        return "hello";
    }

}