<?php

namespace TIS\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class DefaultController //implements ContainerAwareInterface
{
    //private $container;

    private $em;

//    public function setContainer(ContainerInterface $container = null)
//    {
//        $this->container = $container;
//    }

    public function setEm($em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function indexAction($name)
    {
        //$em = $this->container->get('doctrine');

        return array('name' => $name);
    }
}
