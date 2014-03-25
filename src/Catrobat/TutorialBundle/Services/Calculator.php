<?php

namespace Catrobat\TutorialBundle\Services;

class Calculator
{

    public function add($argument1, $argument2)
    {
        $sum = ($argument1+$argument2);
        return ($sum < 0) ? 0 : $sum;
    }
}
