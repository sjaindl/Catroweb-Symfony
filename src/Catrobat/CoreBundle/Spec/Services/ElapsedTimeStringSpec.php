<?php

namespace Catrobat\CoreBundle\Spec\Services;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ElapsedTimeStringSpec extends ObjectBehavior
{
  /**
   * @param \Symfony\Component\Translation\Translator $translator
   */
  function let($translator)
  {
    $this->beConstructedWith($translator);
  }
  
  function it_is_initializable()
  {
    $this->shouldHaveType('Catrobat\CoreBundle\Services\ElapsedTimeString');
  }
  
  function it_returns_the_elapsed_time_since_timestamps($translator)
  {
    $translator->transChoice(Argument::exact("time.minutes.ago"),Argument::exact(0),Argument::any(),Argument::any())->willReturn("< 1 minute ago");
    $translator->transChoice(Argument::exact("time.minutes.ago"),Argument::exact(1),Argument::any(),Argument::any())->willReturn("1 minute ago");
    $translator->transChoice(Argument::exact("time.minutes.ago"),Argument::exact(5),Argument::any(),Argument::any())->willReturn("5 minutes ago");
    
    $this->getElapsedTime(time() - 10)->shouldReturn("< 1 minute ago");
    $this->getElapsedTime(time() - 80)->shouldReturn("1 minute ago");
    $this->getElapsedTime(time() - 60 * 5)->shouldReturn("5 minutes ago");
    $this->getElapsedTime(time() - 60 * 5 - 10)->shouldReturn("5 minutes ago");
  }
}