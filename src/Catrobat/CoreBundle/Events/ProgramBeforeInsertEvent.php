<?php
namespace Catrobat\CoreBundle\Events;

use Symfony\Component\EventDispatcher\Event;
use Catrobat\CoreBundle\Model\ExtractedCatrobatFile;

class ProgramBeforeInsertEvent extends Event
{
  
  protected $extracted_file; 
  
  function __construct(ExtractedCatrobatFile $extracted_file)
  {
    $this->extracted_file = $extracted_file;
  }

  public function getExtractedFile()
  {
    return $this->extracted_file;
  }
}