<?php

namespace App\Utils;

class GetOrMakeResult
{
    public $year;
    public $isNew;

    /**
     * @param $year
     * @param $isNew
     */
    public function __construct($year, $isNew)
    {
        $this->year = $year;
        $this->isNew = $isNew;
    }


}