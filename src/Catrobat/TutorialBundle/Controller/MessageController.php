<?php

namespace Catrobat\TutorialBundle\Controller;

use Symfony\Component\Templating\EngineInterface;

class MessageController {

    protected $templating;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    public function say($extraParameter)
    {
        $message = "Hello from Message Controller with parameter: " . $extraParameter;
        return $this->templating->renderResponse('CatrobatTutorialBundle:Message:say.html.twig', array("message" => $message));
    }

}