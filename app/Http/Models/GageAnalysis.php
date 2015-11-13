<?php
/**
 * Created by PhpStorm.
 * User: ybhavnasi
 * Date: 11/6/15
 * Time: 12:34 PM
 */

namespace App\Http\Models;
use App\Http\Models\PathviewAnalysis;

class GageAnalysis
{

    private $geneIdType;
    private $species;
    private $reference;
    private $sample;
    private $cutoff;
    private $setSizeMin;
    private $setSizeMax;
    private $compare;
    private $test2d;
    private $rankTest;
    private $useFold;
    private $test;
    private $dopathview;
    private $normalizedData;
    private $countData;
    private $logTransformed;
    private $geneSetCategory;
    private $geneSet;
    private $customGeneIDFileExtension;
    private $pathviewAnalysisFlag;
    private $pathviewAnalyisObject;

    /**
     * GageAnalysis constructor.
     * @param $geneIdType
     */



    public function generateArgument(){

        //Constructing argument out of class variables
        $argument = "";

        $argument .= "reference:".$this->reference.";";
        $argument .= "sample:".$this->sample.";";
        $argument .= "filename:".$this->assayData.";";
        $argument .= "destFile:".$this->destFile.";";
        $argument .= "destDir:".$this->destDir.";";
        $argument .= "geneextension:".$this->fileExtension.";";
        $argument .= "geneSetCategory:".$this->geneSetCategory.";";
        $argument .= "geneSet:".$this->geneSet.";";
        $argument .= "species:".$this->species.";";
        $argument .= "cutoff:".$this->cutoff.";";
        $argument .= "geneIdType:".$this->geneIdType.";";
        if(strcmp($this->geneIdType,"custom")==0)
        {
            $argument .= "gsfn:".$this->customGeneIDFile.";";
            $argument .= "gsetextension:".$this->customGeneIDFileExtension.";";
        }
        $argument .= "setSizeMin:".$this->setSizeMin.";";
        $argument .= "setSizeMax:".$this->setSizeMax.";";
        $argument .= "compare:".$this->compare.";";
        $argument .= "test.2d:".$this->test2d.";";
        $argument .= "rankTest:".$this->rankTest.";";
        $argument .= "useFold:".$this->useFold.";";
        $argument .= "test:".$this->test.";";
        $argument .= "normalized:".$this->normalizedData.";";
        $argument .= "count.data:".$this->countData.";";
        $argument .= "do.log:".$this->logTransformed.";";
        $argument .= "dopathview:".$this->dopathview.";";
        if(strcmp($this->dopathview,"T")==0)
        $argument .= "data.type:".$this->dataType.";";

        //pathview

        if(strcmp($this->pathviewAnalysisFlag,'T') ==0 )
	{



	$analysis_object = $this->pathviewAnalyisObject;

        $pathview_argument = "";

        $pathview_argument .= "kegg:".$analysis_object->getKeggFlag().";";
        $pathview_argument .= "layer:".$analysis_object->getLayerFlag().";";
        $pathview_argument .= "split:".$analysis_object->getSplitFlag().";";
        $pathview_argument .= "expand:".$analysis_object->getExpandFlag().";";
        $pathview_argument .= "multistate:".$analysis_object->getMultiStateFlag().";";
        $pathview_argument .= "matchd:".$analysis_object->getMatchDataFlag().";";
        $pathview_argument .= "gdisc:".$analysis_object->getGeneDiscreteFlag().";";
        $pathview_argument .= "cdisc:".$analysis_object->getCompoundDiscreteFlag().";";
        $pathview_argument .= "kpos:".$analysis_object->getKeyPosition().";";
        $pathview_argument .= "pos:".$analysis_object->getSignaturePosition().";";
        $pathview_argument .= "offset:".$analysis_object->getOffset().";";
        $pathview_argument .= "align:".$analysis_object->getKeyAlign().";";
        $pathview_argument .= "glmt:".$analysis_object->getGeneLimit().";";
        $pathview_argument .= "gbins:".$analysis_object->getGeneBins().";";
        $pathview_argument .= "clmt:".$analysis_object->getCompoundLimit().";";
        $pathview_argument .= "cbins:".$analysis_object->getGeneBins().";";
        $pathview_argument .= "glow:".$analysis_object->getGeneLow().";";
        $pathview_argument .= "gmid:".$analysis_object->getGeneMid().";";
        $pathview_argument .= "ghigh:".$analysis_object->getGeneHigh().";";
        $pathview_argument .= "clow:".$analysis_object->getCompoundLow().";";
        $pathview_argument .= "cmid:".$analysis_object->getCompoundMid().";";
        $pathview_argument .= "chigh:".$analysis_object->getCompoundHigh().";";
        $pathview_argument .= "nsum:".$analysis_object->getNodeSum().";";
        $pathview_argument .= "ncolor:".$analysis_object->getNaColor().";";

	}
        if(strcmp($this->pathviewAnalysisFlag,'T') ==0 )
        {
            $argument .= $pathview_argument;
        }

        return $argument;
    }
    /**
     * @return mixed
     */
    public function getPathviewAnalyisObject()
    {
        return $this->pathviewAnalyisObject;
    }

    /**
     * @param mixed $pathviewAnalyisObject
     */
    public function setPathviewAnalyisObject($pathviewAnalyisObject)
    {
        $this->pathviewAnalyisObject = $pathviewAnalyisObject;
    }

    /**
     * @return mixed
     */
    public function getPathviewAnalysisFlag()
    {
        return $this->pathviewAnalysisFlag;
    }

    /**
     * @param mixed $pathviewAnalysisFlag
     */
    public function setPathviewAnalysisFlag($pathviewAnalysisFlag)
    {
        $this->pathviewAnalysisFlag = $pathviewAnalysisFlag;
    }

    /**
     * @return mixed
     */
    public function getCustomGeneIDFileExtension()
    {
        return $this->customGeneIDFileExtension;
    }

    /**
     * @param mixed $customGeneIDFileExtension
     */
    public function setCustomGeneIDFileExtension($customGeneIDFileExtension)
    {
        $this->customGeneIDFileExtension = $customGeneIDFileExtension;
    }
    private $customGeneIDFile;

    /**
     * @return mixed
     */
    public function getCustomGeneIDFile()
    {
        return $this->customGeneIDFile;
    }

    /**
     * @param mixed $customGeneIDFile
     */
    public function setCustomGeneIDFile($customGeneIDFile)
    {
        $this->customGeneIDFile = $customGeneIDFile;
    }

    /**
     * @return mixed
     */
    public function getGeneSet()
    {
        return $this->geneSet;
    }

    /**
     * @param mixed $geneSet
     */
    public function setGeneSet($geneSet)
    {
        $this->geneSet = $geneSet;
    }
    /**
     * @return mixed
     */
    public function getGeneSetCategory()
    {
        return $this->geneSetCategory;
    }

    /**
     * @param mixed $geneSetCategory
     */
    public function setGeneSetCategory($geneSetCategory)
    {
        $this->geneSetCategory = $geneSetCategory;
    }


    /**
     * @return mixed
     */
    public function getFileExtension()
    {
        return $this->fileExtension;
    }

    /**
     * @param mixed $fileExtension
     */
    public function setFileExtension($fileExtension)
    {
        $this->fileExtension = $fileExtension;
    }
    private $dataType;
    private $assayData;
    private $destFile;
    private $destDir;
    private $fileExtension;



    /**
     * @return mixed
     */
    public function getDestDir()
    {
        return $this->destDir;
    }

    /**
     * @param mixed $destDir
     */
    public function setDestDir($destDir)
    {
        $this->destDir = $destDir;
    }


    /**
     * @return mixed
     */
    public function getDestFile()
    {
        return $this->destFile;
    }

    /**
     * @param mixed $destFile
     */
    public function setDestFile($destFile)
    {
        $this->destFile = $destFile;
    }

    /**
     * @return mixed
     */
    public function getAssayData()
    {
        return $this->assayData;
    }

    /**
     * @param mixed $assayData
     */
    public function setAssayData($assayData)
    {
        $this->assayData = $assayData;
    }
    /**
     * @return mixed
     */
    public function getGeneIdType()
    {
        return $this->geneIdType;
    }

    /**
     * @param mixed $geneIdType
     */
    public function setGeneIdType($geneIdType)
    {
        $this->geneIdType = $geneIdType;
    }

    /**
     * @return mixed
     */
    public function getDataType()
    {
        return $this->dataType;
    }

    /**
     * @param mixed $dataType
     */
    public function setDataType($dataType)
    {
        $this->dataType = $dataType;
    }

    /**
     * @return mixed
     */
    public function getLogTransformed()
    {
        return $this->logTransformed;
    }

    /**
     * @param mixed $logTransformed
     */
    public function setLogTransformed($logTransformed)
    {
        $this->logTransformed = $logTransformed;
    }

    /**
     * @return mixed
     */
    public function getCountData()
    {
        return $this->countData;
    }

    /**
     * @param mixed $countData
     */
    public function setCountData($countData)
    {
        $this->countData = $countData;
    }

    /**
     * @return mixed
     */
    public function getNormalizedData()
    {
        return $this->normalizedData;
    }

    /**
     * @param mixed $normalizedData
     */
    public function setNormalizedData($normalizedData)
    {
        $this->normalizedData = $normalizedData;
    }

    /**
     * @return mixed
     */
    public function getDopathview()
    {
        return $this->dopathview;
    }

    /**
     * @param mixed $dopathview
     */
    public function setDopathview($dopathview)
    {
        $this->dopathview = $dopathview;
    }

    /**
     * @return mixed
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * @param mixed $test
     */
    public function setTest($test)
    {
        $this->test = $test;
    }

    /**
     * @return mixed
     */
    public function getUseFold()
    {
        return $this->useFold;
    }

    /**
     * @param mixed $useFold
     */
    public function setUseFold($useFold)
    {
        $this->useFold = $useFold;
    }

    /**
     * @return mixed
     */
    public function getRankTest()
    {
        return $this->rankTest;
    }

    /**
     * @param mixed $rankTest
     */
    public function setRankTest($rankTest)
    {
        $this->rankTest = $rankTest;
    }

    /**
     * @return mixed
     */
    public function getTest2d()
    {
        return $this->test2d;
    }

    /**
     * @param mixed $test2d
     */
    public function setTest2d($test2d)
    {
        $this->test2d = $test2d;
    }

    /**
     * @return mixed
     */
    public function getCompare()
    {
        return $this->compare;
    }

    /**
     * @param mixed $compare
     */
    public function setCompare($compare)
    {
        $this->compare = $compare;
    }

    /**
     * @return mixed
     */
    public function getSetSizeMax()
    {
        return $this->setSizeMax;
    }

    /**
     * @param mixed $setSizeMax
     */
    public function setSetSizeMax($setSizeMax)
    {
        $this->setSizeMax = $setSizeMax;
    }

    /**
     * @return mixed
     */
    public function getCutoff()
    {
        return $this->cutoff;
    }

    /**
     * @param mixed $cutoff
     */
    public function setCutoff($cutoff)
    {
        $this->cutoff = $cutoff;
    }

    /**
     * @return mixed
     */
    public function getSetSizeMin()
    {
        return $this->setSizeMin;
    }

    /**
     * @param mixed $setSizeMin
     */
    public function setSetSizeMin($setSizeMin)
    {
        $this->setSizeMin = $setSizeMin;
    }

    /**
     * @return mixed
     */
    public function getSample()
    {
        return $this->sample;
    }

    /**
     * @param mixed $sample
     */
    public function setSample($sample)
    {
        $this->sample = $sample;
    }

    /**
     * @return mixed
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param mixed $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
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
        $this->species = $species;
    }





}
