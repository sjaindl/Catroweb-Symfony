<?php

namespace Catrobat\TutorialBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CatrobatTutorialBundle:Default:index.html.twig', array('name' => $name));
    }
}
