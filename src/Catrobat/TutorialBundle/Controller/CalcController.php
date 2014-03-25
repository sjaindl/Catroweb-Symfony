<?php

namespace Catrobat\TutorialBundle\Controller;

use Catrobat\TutorialBundle\Services\Calculator;
use Symfony\Component\Templating\EngineInterface;

class CalcController {

    protected $templating;
    protected $calculator;

    public function __construct(EngineInterface $templating, Calculator $calculator )
    {
        $this->templating = $templating;
        $this->calculator = $calculator;
    }

    public function add($num1 = 0, $num2 = 0)
    {
        $message = "Sorry no time to say hello, must add the input num1=" . $num1 . " and num2=" .$num2 . "! Its=" . $this->calculator->add($num1,$num2);
        return $this->templating->renderResponse('CatrobatTutorialBundle:Message:say.html.twig', array("message" => $message));
    }

}