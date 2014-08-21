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
  
  function it_returns_the_elapsed_time_since_timestamps_in_minutes($translator)
  {
    $translator->transChoice(Argument::exact("time.minutes.ago"),Argument::exact(0),Argument::any(),Argument::any())->willReturn("< 1 minute ago");
    $translator->transChoice(Argument::exact("time.minutes.ago"),Argument::exact(1),Argument::any(),Argument::any())->willReturn("1 minute ago");
    $translator->transChoice(Argument::exact("time.minutes.ago"),Argument::exact(5),Argument::any(),Argument::any())->willReturn("5 minutes ago");
    $translator->transChoice(Argument::exact("time.minutes.ago"),Argument::exact(59),Argument::any(),Argument::any())->willReturn("59 minutes ago");
    //$translator->transChoice(Argument::exact("time..ago"),Argument::exact(1),Argument::any(),Argument::any())->willReturn("1 hour ago");
   // $translator->transChoice(Argument::exact("time.hours.ago"),Argument::exact(1),Argument::any(),Argument::any())->willReturn("1 hour ago");
    
    $this->getElapsedTime(time() - 10)->shouldReturn("< 1 minute ago");
    $this->getElapsedTime(time() - 80)->shouldReturn("1 minute ago");
    $this->getElapsedTime(time() - 60 * 5)->shouldReturn("5 minutes ago");
    $this->getElapsedTime(time() - 60 * 5 - 10)->shouldReturn("5 minutes ago");
    $this->getElapsedTime(time() - 60 * 59)->shouldReturn("59 minutes ago");
   // $this->getElapsedTime(time() - 3600)->shouldReturn("1 hour ago");
  }

  function it_returns_the_elapsed_time_since_timestamps_in_hours($translator)
  {
    $translator->transChoice(Argument::exact("time.hours.ago"),Argument::exact(1),Argument::any(),Argument::any())->willReturn("1 hour ago");
    $translator->transChoice(Argument::exact("time.hours.ago"),Argument::exact(5),Argument::any(),Argument::any())->willReturn("5 hours ago");
    $translator->transChoice(Argument::exact("time.hours.ago"),Argument::exact(23),Argument::any(),Argument::any())->willReturn("23 hours ago");

    $this->getElapsedTime(time() - 3600)->shouldReturn("1 hour ago");
    $this->getElapsedTime(time() - 3600 * 5)->shouldReturn("5 hours ago");
    $this->getElapsedTime(time() - 3600 * 5 - 10)->shouldReturn("5 hours ago");
    $this->getElapsedTime(time() - 3600 * 23)->shouldReturn("23 hours ago");
  }

  function it_returns_the_elapsed_time_since_timestamps_in_days($translator)
  {
    $translator->transChoice(Argument::exact("time.days.ago"),Argument::exact(1),Argument::any(),Argument::any())->willReturn("1 day ago");
    $translator->transChoice(Argument::exact("time.days.ago"),Argument::exact(5),Argument::any(),Argument::any())->willReturn("5 days ago");
    $translator->transChoice(Argument::exact("time.days.ago"),Argument::exact(6),Argument::any(),Argument::any())->willReturn("6 days ago");

    $this->getElapsedTime(time() - 86400)->shouldReturn("1 day ago");
    $this->getElapsedTime(time() - 86400 * 5)->shouldReturn("5 days ago");
    $this->getElapsedTime(time() - 86400 * 5 - 10)->shouldReturn("5 days ago");
    $this->getElapsedTime(time() - 86400 * 6)->shouldReturn("6 days ago");
  }

  function it_returns_the_elapsed_time_since_timestamps_in_months($translator)
  {
    $translator->transChoice(Argument::exact("time.months.ago"),Argument::exact(1),Argument::any(),Argument::any())->willReturn("1 month ago");
    $translator->transChoice(Argument::exact("time.months.ago"),Argument::exact(6),Argument::any(),Argument::any())->willReturn("6 months ago");
    $translator->transChoice(Argument::exact("time.months.ago"),Argument::exact(11),Argument::any(),Argument::any())->willReturn("11 months ago");

    $this->getElapsedTime(time() - 2629800)->shouldReturn("1 month ago");
    $this->getElapsedTime(time() - 2629800 * 6)->shouldReturn("6 months ago");
    $this->getElapsedTime(time() - 2629800 * 6 - 10)->shouldReturn("6 months ago");
    $this->getElapsedTime(time() - 2629800 * 11)->shouldReturn("11 months ago");
  }

  function it_returns_the_elapsed_time_since_timestamps_in_years($translator)
  {
    $translator->transChoice(Argument::exact("time.years.ago"),Argument::exact(1),Argument::any(),Argument::any())->willReturn("1 year ago");
    $translator->transChoice(Argument::exact("time.years.ago"),Argument::exact(3),Argument::any(),Argument::any())->willReturn("3 years ago");
    $translator->transChoice(Argument::exact("time.years.ago"),Argument::exact(100),Argument::any(),Argument::any())->willReturn("100 years ago");

    $this->getElapsedTime(time() - 31557600)->shouldReturn("1 year ago");
    $this->getElapsedTime(time() - 31557600 * 3)->shouldReturn("3 years ago");
    $this->getElapsedTime(time() - 31557600 * 3 - 10)->shouldReturn("3 years ago");
    $this->getElapsedTime(time() - 31557600 * 100)->shouldReturn("100 years ago");
  }

}