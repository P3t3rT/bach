<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Opus;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class ConverterController extends Controller
{
    /**
     * @Route("/convert")
     *
     */
    public function convertAction()
    {
        return $this->render('::blank_page.html.twig');
    }

    /**
     * @Route("/bachtoopus")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function bachToOpusAction()
    {
        try {
            $recs = $this->getDoctrine()
                         ->getRepository('AppBundle:Bach')
                         ->findTitles();

            $em = $this->getDoctrine()->getManager();

            foreach ($recs as $rec) {
//                $opus = new Opus();

                $pos = strpos($rec->getTitle(), ' ');
                $part1 = trim(substr($rec->getTitle(),0,$pos));
                $part2 = trim(substr($rec->getTitle(),$pos));
                if (strlen($part2) > 63) {
                   // echo $part1 . ' ' . strlen($part2) . ' ' . $part2 . "<br>";
                    $repo = $em->getRepository('AppBundle:Opus');
                    $rec = $repo->findOneBy(array('opus' => $part1));

                    $rec->setTitle($part2);
                    //$em->persist($rec);

                }
            }

            $em->flush();

            return new Response('bachtoopus done');

        } catch (ORMException $e) {
            $logger = $this->get('logger');
            $logger->error($e->getMessage());

            return new Response('Error: '.$e->getMessage());
        }
    }

    /**
     * @Route("/opuscantate")
     *
     * @return Response
     */
    public function opusCantateAction()
    {
        try {

            $em = $this->getDoctrine()->getManager();
            $recs = $em->getRepository('AppBundle:Opus')
                       ->findCantates();

            foreach ($recs as $rec) {
                $aStr = array();
                $aStr = explode(',',$rec->getTitle());

                if ($aStr) {
                    $part = implode(", ",$aStr);
                    $rec->setTitle($part);
                    //$em->persist($rec);
                }
            }

            $em->flush();

            return new Response('opusCantate done');

        } catch (ORMException $e) {
            $logger = $this->get('logger');
            $logger->error($e->getMessage());

            return new Response('Error: '.$e->getMessage());
        }
    }
}
