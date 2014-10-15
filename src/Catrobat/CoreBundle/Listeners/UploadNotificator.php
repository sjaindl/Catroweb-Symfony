<?php
namespace Catrobat\CoreBundle\Listeners;

use Catrobat\CoreBundle\Events\ProgramInsertEvent;
use Catrobat\CoreBundle\Model\UserManager;
use Catrobat\CoreBundle\Entity\User;
use Monolog\Logger;

class UploadNotificator
{
  private $logger;

  function __construct(Logger $logger)
  {
    $this->logger = $logger;
  }

  function onProgramInsertEvent(ProgramInsertEvent $event)
  {
    $manager = new UserManager();
    $users = $manager->findUsersBy("uploaded_notification = 1");
    foreach($users as $user)
    {
      /*@var $user User*/
      $this->$logger->addDebug($user->getUsername(). "would have got mail");
    }
  }
}
