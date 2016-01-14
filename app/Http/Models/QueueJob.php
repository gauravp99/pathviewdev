<?php
/**
 * Created by PhpStorm.
 * User: ybhavnasi
 * Date: 1/12/16
 * Time: 10:27 AM
 */

namespace App\Http\Models;


class QueueJob
{

    public $analysis_id;
    public $analysis_origin;

    /**
     * QueueJob constructor.
     * @param $analysis_id
     * @param $analysis_origin
     */
    public function __construct($analysis_id, $analysis_origin)
    {
        $this->analysis_id = $analysis_id;
        $this->analysis_origin = $analysis_origin;
    }


    /**
     * @return mixed
     */
    public function getAnalysisId()
    {
        return $this->analysis_id;
    }

    /**
     * @param mixed $analysis_id
     */
    public function setAnalysisId($analysis_id)
    {
        $this->analysis_id = $analysis_id;
    }

    /**
     * @return mixed
     */
    public function getAnalysisOrigin()
    {
        return $this->analysis_origin;
    }

    /**
     * @param mixed $analysis_origin
     */
    public function setAnalysisOrigin($analysis_origin)
    {
        $this->analysis_origin = $analysis_origin;
    }



}