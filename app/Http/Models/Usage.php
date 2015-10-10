<?php
/**
 * Created by PhpStorm.
 * User: ybhavnasi
 * Date: 9/24/15
 * Time: 3:55 PM
 */

namespace App\Http\Models;


class Usage
{

    /**
     * @var
     */
    private  $date;
    /**
     * @var
     */
    private $ip;
    /**
     * @var
     */
    private $usage;

    /**
     * @param $date
     * @param $ip
     * @param $usage
     */
    function __construct($date,$ip,$usage) {
        $this->date = $date;
        $this->ip = $ip;
        $this->usage = $usage;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return mixed
     */
    public function getUsage()
    {
        return $this->usage;
    }

    /**
     * @param mixed $usage
     */
    public function setUsage($usage)
    {
        $this->usage = $usage;
    }







}