<?php

namespace BCLib\Indipetae\ViewModel;

class Range
{
    public $min;
    public $max;

    public function __construct($min, $max)
    {
        $this->min = $min;
        $this->max = $max;
    }
}