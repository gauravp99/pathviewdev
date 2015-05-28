<?php
/**
 * Created by PhpStorm.
 * User: ybhavnasi
 * Date: 5/28/15
 * Time: 9:21 AM
 */

namespace App\Http\Controllers;


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
    function __toString()
    {
        // TODO: Implement __toString() method.
        return "hello";
    }

    /**
     * @return mixed
     */
    public function getNacolor()
    {
        return $this->nacolor;
    }

    /**
     * @param mixed $nacolor
     */
    public function setNacolor($nacolor)
    {

        $this->nacolor = $nacolor;
    }

    /**
     * @return mixed
     */
    public function getNodesum()
    {
        return $this->nodesum;
    }

    /**
     * @param mixed $nodesum
     */
    public function setNodesum($nodesum)
    {
        $this->nodesum = $nodesum;
    }

    /**
     * @return mixed
     */
    public function getChigh()
    {
        return $this->chigh;
    }

    /**
     * @param mixed $chigh
     */
    public function setChigh($chigh)
    {
        $this->chigh = $chigh;
    }

    /**
     * @return mixed
     */
    public function getCmid()
    {
        return $this->cmid;
    }

    /**
     * @param mixed $cmid
     */
    public function setCmid($cmid)
    {
        $this->cmid = $cmid;
    }

    /**
     * @return mixed
     */
    public function getClow()
    {
        return $this->clow;
    }

    /**
     * @param mixed $clow
     */
    public function setClow($clow)
    {
        $this->clow = $clow;
    }

    /**
     * @return mixed
     */
    public function getCbins()
    {
        return $this->cbins;
    }

    /**
     * @param mixed $cbins
     */
    public function setCbins($cbins)
    {
        $this->cbins = $cbins;
    }

    /**
     * @return mixed
     */
    public function getClmt()
    {
        return $this->clmt;
    }

    /**
     * @param mixed $clmt
     */
    public function setClmt($clmt)
    {
        $this->clmt = $clmt;
    }

    /**
     * @return mixed
     */
    public function getGhigh()
    {
        return $this->ghigh;
    }

    /**
     * @param mixed $ghigh
     */
    public function setGhigh($ghigh)
    {
        $this->ghigh = $ghigh;
    }

    /**
     * @return mixed
     */
    public function getGmid()
    {
        return $this->gmid;
    }

    /**
     * @param mixed $gmid
     */
    public function setGmid($gmid)
    {
        $this->gmid = $gmid;
    }

    /**
     * @return mixed
     */
    public function getGlow()
    {
        return $this->glow;
    }

    /**
     * @param mixed $glow
     */
    public function setGlow($glow)
    {
        $this->glow = $glow;
    }

    /**
     * @return mixed
     */
    public function getGbins()
    {
        return $this->gbins;
    }

    /**
     * @param mixed $gbins
     */
    public function setGbins($gbins)
    {
        $this->gbins = $gbins;
    }

    /**
     * @return mixed
     */
    public function getGlmt()
    {
        return $this->glmt;
    }

    /**
     * @param mixed $glmt
     */
    public function setGlmt($glmt)
    {
        $this->glmt = $glmt;
    }

    /**
     * @return mixed
     */
    public function getAlign()
    {
        return $this->align;
    }

    /**
     * @param mixed $align
     */
    public function setAlign($align)
    {
        $this->align = $align;
    }

    /**
     * @return mixed
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param mixed $offset
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
    }

    /**
     * @return mixed
     */
    public function getPos()
    {
        return $this->pos;
    }

    /**
     * @param mixed $pos
     */
    public function setPos($pos)
    {
        $this->pos = $pos;
    }

    /**
     * @return mixed
     */
    public function getKpos()
    {
        return $this->kpos;
    }

    /**
     * @param mixed $kpos
     */
    public function setKpos($kpos)
    {
        $this->kpos = $kpos;
    }

    /**
     * @return mixed
     */
    public function getCdisc()
    {
        return $this->cdisc;
    }

    /**
     * @param mixed $cdisc
     */
    public function setCdisc($cdisc)
    {
        $this->cdisc = $cdisc;
    }

    /**
     * @return mixed
     */
    public function getGdisc()
    {
        return $this->gdisc;
    }

    /**
     * @param mixed $gdisc
     */
    public function setGdisc($gdisc)
    {
        $this->gdisc = $gdisc;
    }

    /**
     * @return mixed
     */
    public function getMatchd()
    {
        return $this->matchd;
    }

    /**
     * @param mixed $matchd
     */
    public function setMatchd($matchd)
    {
        $this->matchd = $matchd;
    }

    /**
     * @return mixed
     */
    public function getMultistate()
    {
        return $this->multistate;
    }

    /**
     * @param mixed $multistate
     */
    public function setMultistate($multistate)
    {
        $this->multistate = $multistate;
    }

    /**
     * @return mixed
     */
    public function getExpand()
    {
        return $this->expand;
    }

    /**
     * @param mixed $expand
     */
    public function setExpand($expand)
    {
        $this->expand = $expand;
    }

    /**
     * @return mixed
     */
    public function getSplit()
    {
        return $this->split;
    }

    /**
     * @param mixed $split
     */
    public function setSplit($split)
    {
        $this->split = $split;
    }

    /**
     * @return mixed
     */
    public function getLayer()
    {
        return $this->layer;
    }

    /**
     * @param mixed $layer
     */
    public function setLayer($layer)
    {
        $this->layer = $layer;
    }

    /**
     * @return mixed
     */
    public function getKegg()
    {
        return $this->kegg;
    }

    /**
     * @param mixed $kegg
     */
    public function setKegg($kegg)
    {
        $this->kegg = $kegg;
    }

    /**
     * @return mixed
     */
    public function getSuffix()
    {
        return $this->suffix;
    }

    /**
     * @param mixed $suffix
     */
    public function setSuffix($suffix)
    {
        $this->suffix = $suffix;
    }

    /**
     * @return array
     */
    public function getPathway()
    {
        return $this->pathway;
    }

    /**
     * @param array $pathway
     */
    public function setPathway($pathway)
    {
        $this->pathway = $pathway;
    }

    /**
     * @return mixed
     */
    public function getSpecies()
    {
        return $this->species;
    }

    /**
     * @param mixed $species
     */
    public function setSpecies($species)
    {
        $val = DB::select(DB::raw("select species_id from Species where species_id like '$species' LIMIT 1"));
        if (sizeof($val) > 0) {
            $this->species = $species;
        } else {
            array_push($errors, "Entered Species ID doesn't exist");
            $err_atr["species"] = 1;
        }

    }

    /**
     * @return mixed
     */
    public function getCpdid()
    {
        return $this->cpdid;
    }

    /**
     * @param mixed $cpdid
     */
    public function setCpdid($cpdid)
    {
        $val = DB::select(DB::raw("select cmpdid  from compound where cmpdid  like '$cpdid' LIMIT 1 "));
        if (sizeof($val) == 0) {
            array_push($errors, "Entered compound ID doesn't exist");
            $err_atr["cpdid"] = 1;
        } else {
        $this->cpdid = $cpdid;

        }

    }

    /**
     * @return mixed
     */
    public function getGeneid()
    {
        return $this->geneid;
    }

    /**
     * @param mixed $geneid
     */
    public function setGeneid($geneid)
    {
        $val = DB::select(DB::raw("select geneid  from gene where geneid  like '$geneid' LIMIT 1 "));

        if (sizeof($val) == 0) {
            array_push($errors, "Entered Gene ID doesn't exist");
            $err_atr["geneid"] = 1;

        } else {
            $this->geneid = $val[0]->geneid;
        }

    }

    /**
     * @return mixed
     */
    public function getCfile()
    {
        return $this->cfile;
    }

    /**
     * @param mixed $cfile
     */
    public function setCfile($cfile)
    {
        $this->cfile = $cfile;
    }

    /**
     * @return mixed
     */
    public function getGfile()
    {
        return $this->gfile;
    }

    /**
     * @param mixed $gfile
     */
    public function setGfile($gfile)
    {
        $this->gfile = $gfile;
    }


}