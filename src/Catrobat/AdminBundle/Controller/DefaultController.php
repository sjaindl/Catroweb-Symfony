<?php

namespace Catrobat\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
      $admin_pool = $this->get('sonata.admin.pool');
      return $this->render('CatrobatAdminBundle:Default:index.html.twig', array('name' => $name, 'admin_pool' => $admin_pool));
    }
}
