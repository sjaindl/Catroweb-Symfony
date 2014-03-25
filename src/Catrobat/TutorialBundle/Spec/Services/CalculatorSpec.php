<?php

namespace Catrobat\TutorialBundle\Spec\Services;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CalculatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Catrobat\TutorialBundle\Services\Calculator');
    }

    function it_adds_two_numbers()
    {
        $this->add(1,2)->shouldReturn(3);
        $this->add(4,2)->shouldReturn(6);
        $this->add(2,10)->shouldReturn(12);
        $this->add(3,9)->shouldReturn(12);
        $this->add(-5,5)->shouldReturn(0);
    }

    function it_returns_zero_instead_of_negative_numbers()
    {
        $this->add(-10,2)->shouldReturn(0);
    }

}
