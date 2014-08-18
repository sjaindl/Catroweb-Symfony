<?php


namespace Catrobat\CoreBundle\Spec\Services;


use PhpSpec\ObjectBehavior;

class BadWordFilterSpec extends ObjectBehavior {

    function it_is_initializable()
    {
        $this->shouldHaveType('Catrobat\CoreBundle\Services\BadWordFilter');
    }
} 