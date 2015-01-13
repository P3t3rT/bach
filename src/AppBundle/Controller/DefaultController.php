<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/hello/{name}")
     * @Template()
     *
     * @param $name
     * @return array
     */
    public function indexAction($name)
    {
        return array('name' => $name);
    }

    /**
     * @Route("/empty")
     *
     */
    public function emptyAction()
    {
        return $this->render('::blank_page.html.twig');
    }
}
