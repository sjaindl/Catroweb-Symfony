<?php

namespace Catrobat\CoreBundle\Services;

class ElapsedTimeString
{
  private $translator;
  
  public function __construct(\Symfony\Component\Translation\Translator $translator)
  {
    $this->translator = $translator;
  }
  
  public function getElapsedTime($timestamp)
  {
    $elapsed = time() - $timestamp;

    if ($elapsed <= 3540)
    {
      $minutes = floor($elapsed / 60);
      return $this->translator->transChoice("time.minutes.ago", $minutes, array("count" => $minutes), "catroweb_core");
    }
    else if ($elapsed <= 82800)
    {
      $hours = floor($elapsed / 3600);
      return $this->translator->transChoice("time.hours.ago", $hours, array("count" => $hours), "catroweb_core");
    }
    else if ($elapsed <= 2505600)
    {
      $days = floor($elapsed / 86400);
      return $this->translator->transChoice("time.days.ago", $days, array("count" => $days), "catroweb_core");
    }
    else if ($elapsed <= 28927800)
    {
      $months = floor($elapsed / 2629800);
      return $this->translator->transChoice("time.months.ago", $months, array("count" => $months), "catroweb_core");
    }

    $years = floor($elapsed / 31557600);
    return $this->translator->transChoice("time.years.ago", $years, array("count" => $years), "catroweb_core");
  }

}
