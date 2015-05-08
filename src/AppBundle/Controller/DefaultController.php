<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @return array
     */
    public function indexAction()
    {
        return $this->redirectToRoute('bach_list');
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
