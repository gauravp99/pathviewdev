<?php
/**
 * Created by PhpStorm.
 * User: ybhavnasi
 * Date: 9/25/15
 * Time: 4:13 PM
 */

namespace App\Http\Models;


class PathviewAnalysis
{

    private $geneFileName;
    private $CompoundFileName;
    private $geneId;
    private $compoundId;
    private $speciesID;
    private $pathwayID;
    private $pathwayIDs = array();
    private $suffix;
    private $keggFlag;
    private $autosel;
    private $layerFlag;
    private $splitFlag;
    private $expandFlag;
    private $multiStateFlag;
    private $matchDataFlag;
    private $geneDiscreteFlag;
    private $compoundDiscreteFlag;
    private $keyPosition;
    private $signaturePosition;
    private $offset;
    private $keyAlign;
    private $geneLimit;
    private $geneBins;
    private $compoundLimit;
    private $compoundBins;
    private $geneLow;
    private $geneMid;
    private $geneHigh;
    private $compoundLow;
    private $compoundMid;
    private $compoundHigh;
    private $nodeSum;
    private $naColor;
    private $geneExtension;
    private $compoundExtension;
    private $targetDirectory;
    private $geneReference;
    private $geneSample;
    private $compoundRefeence;
    private $compoundSample;
    private $geneCompare;
    private $compoundCompare;

    /**
     * @return array
     */
    public function getPathwayIDs()
    {
        return $this->pathwayIDs;
    }

    /**
     * @param array $pathwayIDs
     */
    public function setPathwayIDs($pathwayIDs)
    {
        $this->pathwayIDs = $pathwayIDs;
    }

    /**
     * @return mixed
     */
    public function getCompoundLimit()
    {
        return $this->compoundLimit;
    }

    /**
     * @param mixed $compoundLimit
     */
    public function setCompoundLimit($compoundLimit)
    {
        $this->compoundLimit = $compoundLimit;
    }

    /**
     * @return mixed
     */
    public function getCompoundBins()
    {
        return $this->compoundBins;
    }

    /**
     * @param mixed $compoundBins
     */
    public function setCompoundBins($compoundBins)
    {
        $this->compoundBins = $compoundBins;
    }

    /**
     * @return mixed
     */
    public function getGeneExtension()
    {
        return $this->geneExtension;
    }

    /**
     * @param mixed $geneExtension
     */
    public function setGeneExtension($geneExtension)
    {
        $this->geneExtension = $geneExtension;
    }

    /**
     * @return mixed
     */
    public function getCompoundExtension()
    {
        return $this->compoundExtension;
    }

    /**
     * @param mixed $compoundExtension
     */
    public function setCompoundExtension($compoundExtension)
    {
        $this->compoundExtension = $compoundExtension;
    }

    /**
     * @return mixed
     */
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    /**
     * @param mixed $targetDirectory
     */
    public function setTargetDirectory($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * @return mixed
     */
    public function getGeneReference()
    {
        return $this->geneReference;
    }

    /**
     * @param mixed $geneReference
     */
    public function setGeneReference($geneReference)
    {
        $this->geneReference = $geneReference;
    }

    /**
     * @return mixed
     */
    public function getGeneSample()
    {
        return $this->geneSample;
    }

    /**
     * @param mixed $geneSample
     */
    public function setGeneSample($geneSample)
    {
        $this->geneSample = $geneSample;
    }

    /**
     * @return mixed
     */
    public function getCompoundRefeence()
    {
        return $this->compoundRefeence;
    }

    /**
     * @param mixed $compoundRefeence
     */
    public function setCompoundRefeence($compoundRefeence)
    {
        $this->compoundRefeence = $compoundRefeence;
    }

    /**
     * @return mixed
     */
    public function getCompoundSample()
    {
        return $this->compoundSample;
    }

    /**
     * @param mixed $compoundSample
     */
    public function setCompoundSample($compoundSample)
    {
        $this->compoundSample = $compoundSample;
    }

    /**
     * @return mixed
     */
    public function getGeneCompare()
    {
        return $this->geneCompare;
    }

    /**
     * @param mixed $geneCompare
     */
    public function setGeneCompare($geneCompare)
    {
        $this->geneCompare = $geneCompare;
    }

    /**
     * @return mixed
     */
    public function getCompoundCompare()
    {
        return $this->compoundCompare;
    }

    /**
     * @param mixed $compoundCompare
     */
    public function setCompoundCompare($compoundCompare)
    {
        $this->compoundCompare = $compoundCompare;
    }


    /**
     * PathviewAnalysis constructor.
     * @param mixed $string
     */
    public function __construct()
    {
        //split the string using ; and save it object attributes

    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    function __toString()
    {
        return "combined attributes comma separated";

    }

    function createArgument()
    {

        $argument ="";
        $argument .="filename:".$this->geneFileName.";";
        if(!is_null($this->CompoundFileName))
        {
            $argument .="cfilename:".$this->CompoundFileName.";";
            $argument .="cpdextension:".$this->compoundExtension.";";
            if(!is_null($this->compoundRefeence))
                $argument .="cpdref:".$this->compoundRefeence.";";
            else
                $argument .="cpdref:NULL;";
            if(!is_null($this->compoundSample))
                $argument .="cpdsamp:".$this->compoundSample.";";
            else
                $argument .="cpdsamp:NULL;";

            $argument .="cpdcompare:".$this->compoundCompare.";";
        }

        $argument .="geneid:".$this->geneId.";";
        $argument .="cpdid:".$this->compoundId.";";
        $argument .="species:".$this->speciesID.";";
        if($this->autosel)
            $argument .="autosel:T;";
        else
            $argument .="autosel:F;";
        $argument .="pathway:".join(",",$this->pathwayIDs).";";
        $argument .="suffix:".$this->suffix.";";
        if($this->keggFlag)
            $argument .="kegg:T;";
        else
            $argument .="kegg:F;";
        if($this->layerFlag)
            $argument .="layer:T;";
        else
            $argument .="layer:F;";
        if($this->splitFlag)
            $argument .="split:T;";
        else
            $argument .="split:F;";
        if($this->expandFlag)
            $argument .="expand:T;";
        else
            $argument .="expand:F;";
        if($this->multiStateFlag)
            $argument .="multistate:T;";
        else
            $argument .="multistate:F;";
        if($this->matchDataFlag)
            $argument .="matchd:T;";
        else
            $argument .="matchd:F;";
        if($this->geneDiscreteFlag)
            $argument .="gdisc:T;";
        else
            $argument .="gdisc:F;";

        if($this->compoundDiscreteFlag)
            $argument .="cdisc:T;";
        else
            $argument .="cdisc:F;";
        $argument .="kpos:".$this->keyPosition.";";
        $argument .="pos:".$this->signaturePosition.";";
        $argument .="offset:".$this->offset.";";
        $argument .="align:".$this->keyAlign.";";
        $argument .="glmt:".$this->geneLimit.";";
        $argument .="gbins:".$this->geneBins.";";
        $argument .="clmt:".$this->compoundLimit.";";
        $argument .="cbins:".$this->compoundBins.";";
        $argument .="glow:".$this->geneLow.";";
        $argument .="gmid:".$this->geneMid.";";
        $argument .="ghigh:".$this->geneHigh.";";
        $argument .="clow:".$this->compoundLow.";";
        $argument .="cmid:".$this->compoundMid.";";
        $argument .="chigh:".$this->compoundHigh.";";
        $argument .="nsum:".$this->nodeSum.";";
        $argument .="ncolor:".$this->naColor.";";
        $argument .="geneextension:".$this->geneExtension.";";

        $argument .="targedir:".$this->targetDirectory.";";

        if(!is_null($this->geneReference))
            $argument .="generef:".$this->geneReference.";";
        else
            $argument .="generef:NULL;";
        if(!is_null($this->geneSample))
            $argument .="genesamp:".$this->geneSample.";";
        else
            $argument .="genesamp:NULL;";


        $argument .="genecompare:".$this->geneCompare.";";



        return $argument;


    }

    /**
     * @return mixed
     */
    public function getGeneFileName()
    {
        return $this->geneFileName;
    }

    /**
     * @param mixed $geneFileName
     */
    public function setGeneFileName($geneFileName)
    {
        $this->geneFileName = $geneFileName;
    }

    /**
     * @return mixed
     */
    public function getCompoundFileName()
    {
        return $this->CompoundFileName;
    }

    /**
     * @param mixed $CompoundFileName
     */
    public function setCompoundFileName($CompoundFileName)
    {
        $this->CompoundFileName = $CompoundFileName;
    }

    /**
     * @return mixed
     */
    public function getGeneId()
    {
        return $this->geneId;
    }

    /**
     * @param mixed $geneId
     */
    public function setGeneId($geneId)
    {
        $this->geneId = $geneId;
    }

    /**
     * @return mixed
     */
    public function getCompoundId()
    {
        return $this->compoundId;
    }

    /**
     * @param mixed $compoundId
     */
    public function setCompoundId($compoundId)
    {
        $this->compoundId = $compoundId;
    }

    /**
     * @return mixed
     */
    public function getSpeciesID()
    {
        return $this->speciesID;
    }

    /**
     * @param mixed $speciesID
     */
    public function setSpeciesID($speciesID)
    {
        $this->speciesID = $speciesID;
    }

    /**
     * @return mixed
     */
    public function getPathwayID()
    {
        return $this->pathwayID;
    }

    /**
     * @param mixed $pathwayID
     */
    public function setPathwayID($pathwayID)
    {
        $this->pathwayID = $pathwayID;
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
     * @return mixed
     */
    public function getKeggFlag()
    {
        return $this->keggFlag;
    }

    /**
     * @param mixed $keggFlag
     */
    public function setKeggFlag($keggFlag)
    {
        $this->keggFlag = $keggFlag;
    }

    /**
     * @param mixed $autosel
     */
    public function setAutoPathwaySelection($autosel)
    {
        $this->autosel= $autosel;
    }
    /**
     * @return mixed
     */
    public function getLayerFlag()
    {
        return $this->layerFlag;
    }

    /**
     * @param mixed $layerFlag
     */
    public function setLayerFlag($layerFlag)
    {
        $this->layerFlag = $layerFlag;
    }

    /**
     * @return mixed
     */
    public function getSplitFlag()
    {
        return $this->splitFlag;
    }

    /**
     * @param mixed $splitFlag
     */
    public function setSplitFlag($splitFlag)
    {
        $this->splitFlag = $splitFlag;
    }

    /**
     * @return mixed
     */
    public function getExpandFlag()
    {
        return $this->expandFlag;
    }

    /**
     * @param mixed $expandFlag
     */
    public function setExpandFlag($expandFlag)
    {
        $this->expandFlag = $expandFlag;
    }

    /**
     * @return mixed
     */
    public function getMultiStateFlag()
    {
        return $this->multiStateFlag;
    }

    /**
     * @param mixed $multiStateFlag
     */
    public function setMultistateFlag($multiStateFlag)
    {
        $this->multiStateFlag = $multiStateFlag;
    }

    /**
     * @return mixed
     */
    public function getMatchDataFlag()
    {
        return $this->matchDataFlag;
    }

    /**
     * @param mixed $matchDataFlag
     */
    public function setMatchDataFlag($matchDataFlag)
    {
        $this->matchDataFlag = $matchDataFlag;
    }

    /**
     * @return mixed
     */
    public function getGeneDiscreteFlag()
    {
        return $this->geneDiscreteFlag;
    }

    /**
     * @param mixed $geneDiscreteFlag
     */
    public function setGeneDiscreteFlag($geneDiscreteFlag)
    {
        $this->geneDiscreteFlag = $geneDiscreteFlag;
    }

    /**
     * @return mixed
     */
    public function getCompoundDiscreteFlag()
    {
        return $this->compoundDiscreteFlag;
    }

    /**
     * @param mixed $compoundDiscreteFlag
     */
    public function setCompoundDiscreteFlag($compoundDiscreteFlag)
    {
        $this->compoundDiscreteFlag = $compoundDiscreteFlag;
    }

    /**
     * @return mixed
     */
    public function getKeyPosition()
    {
        return $this->keyPosition;
    }

    /**
     * @param mixed $keyPosition
     */
    public function setKeyPosition($keyPosition)
    {
        $this->keyPosition = $keyPosition;
    }

    /**
     * @return mixed
     */
    public function getSignaturePosition()
    {
        return $this->signaturePosition;
    }

    /**
     * @param mixed $signaturePosition
     */
    public function setSignatureposition($signaturePosition)
    {
        $this->signaturePosition = $signaturePosition;
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
    public function getKeyAlign()
    {
        return $this->keyAlign;
    }

    /**
     * @param mixed $keyAlign
     */
    public function setKeyAlign($keyAlign)
    {
        $this->keyAlign = $keyAlign;
    }

    /**
     * @return mixed
     */
    public function getGeneLimit()
    {
        return $this->geneLimit;
    }

    /**
     * @param mixed $geneLimit
     */
    public function setGeneLimit($geneLimit)
    {
        $this->geneLimit = $geneLimit;
    }

    /**
     * @return mixed
     */
    public function getGeneBins()
    {
        return $this->geneBins;
    }

    /**
     * @param mixed $geneBins
     */
    public function setGeneBins($geneBins)
    {
        $this->geneBins = $geneBins;
    }

    /**
     * @return mixed
     */
    public function getGeneLow()
    {
        return $this->geneLow;
    }

    /**
     * @param mixed $geneLow
     */
    public function setGeneLow($geneLow)
    {
        $this->geneLow = $geneLow;
    }

    /**
     * @return mixed
     */
    public function getGeneMid()
    {
        return $this->geneMid;
    }

    /**
     * @param mixed $geneMid
     */
    public function setGeneMid($geneMid)
    {
        $this->geneMid = $geneMid;
    }

    /**
     * @return mixed
     */
    public function getGeneHigh()
    {
        return $this->geneHigh;
    }

    /**
     * @param mixed $geneHigh
     */
    public function setGeneHigh($geneHigh)
    {
        $this->geneHigh = $geneHigh;
    }

    /**
     * @return mixed
     */
    public function getCompoundLow()
    {
        return $this->compoundLow;
    }

    /**
     * @param mixed $compoundLow
     */
    public function setCompoundLow($compoundLow)
    {
        $this->compoundLow = $compoundLow;
    }

    /**
     * @return mixed
     */
    public function getCompoundMid()
    {
        return $this->compoundMid;
    }

    /**
     * @param mixed $compoundMid
     */
    public function setCompoundMid($compoundMid)
    {
        $this->compoundMid = $compoundMid;
    }

    /**
     * @return mixed
     */
    public function getCompoundHigh()
    {
        return $this->compoundHigh;
    }

    /**
     * @param mixed $compoundHigh
     */
    public function setCompoundHigh($compoundHigh)
    {
        $this->compoundHigh = $compoundHigh;
    }

    /**
     * @return mixed
     */
    public function getNodeSum()
    {
        return $this->nodeSum;
    }

    /**
     * @param mixed $nodeSum
     */
    public function setNodeSum($nodeSum)
    {
        $this->nodeSum = $nodeSum;
    }

    /**
     * @return mixed
     */
    public function getNaColor()
    {
        return $this->naColor;
    }

    /**
     * @param mixed $naColor
     */
    public function setNaColor($naColor)
    {
        $this->naColor = $naColor;
    }









}
