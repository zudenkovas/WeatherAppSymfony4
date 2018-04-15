<?php

namespace App\Model;

class NullWeather extends Weather
{
    public function __construct()
    {
        $this->setDayTemp(5);
        $this->setNightTemp(-1);
        $this->setSky(1);
        $this->setDate(new \DateTime('1970-01-01'));
    }
}