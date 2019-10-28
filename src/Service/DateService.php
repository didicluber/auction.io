<?php

namespace App\Service;

class DateService
{
    public function getDay(\DateTime $date)
    {
        return $date->format("d");
    }
}