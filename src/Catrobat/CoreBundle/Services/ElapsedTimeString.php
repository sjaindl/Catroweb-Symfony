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
//     if ($elapsed < 45)
//     {
//       return "< 1 minute ago";
//       // $trans("time.lessthenaminute.ago",{}, "catroweb_core");
//     }
//     else if ($elapsed < 90)
//     {
//       return "1 minute ago";
//     }
//     else if ($elapsed < 360)
//     {
//       return floor($elapsed / 60) . " minutes ago";
//       // $trans("time.lessthenaminute.ago",{"count": }, "catroweb_core");
//     }
    if ($elapsed < 360)
    {
      $minutes = floor($elapsed / 60);
      return $this->translator->transChoice("time.minutes.ago", $minutes, array("count" => $minutes), "catroweb_core");
    }
    return $elapsed;
  }

}
