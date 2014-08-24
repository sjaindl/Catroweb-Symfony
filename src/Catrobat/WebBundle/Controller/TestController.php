<?php
/**
 * Created by IntelliJ IDEA.
 * User: meli
 * Date: 24.08.14
 * Time: 23:14
 */

namespace Catrobat\WebBundle\Controller;


class TestController extends Controller {
    protected $templating;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    public function badWordAction()
    {
        return $this->templating->renderResponse('CatrobatWebBundle::test.html.twig');
    }
} 