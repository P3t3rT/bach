<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Audiotrack;
use AppBundle\Entity\Opus;
use AppBundle\Entity\Part;
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

    /**
     * @Route("/buildpart")
     *
     * @return Response
     */
    public function buildPartAction()
    {
        try {
            $em       = $this->getDoctrine()
                             ->getManager();
            $opusrecs = $em->getRepository('AppBundle:Opus')
                           ->findCantates();

            foreach ($opusrecs as $opus) {
                $bachrecs = $em->getRepository('AppBundle:Bach')
                               ->findParts($opus->getOpus());
                foreach ($bachrecs as $bach) {
                    $pos = strpos($bach->getTitle(), ' ');
                    $part1 = trim(substr($bach->getTitle(),0,$pos));
                    $part2 = trim(substr($bach->getTitle(),$pos));
                    $pos = strpos($part2, ' ');
                    $part3 = substr($part2,0,$pos);
                    $part4= trim(substr($part2,$pos));

                    $pos = strpos($part4, '"');
                    $part5 = substr($part4,0,$pos);
                    $part6= trim(substr($part4,$pos));

                    if ($part6 == '1 Sinfonia' ||
                        $part6 == '1 Sonatina' ||
                        $part6 == '1 Sonata' ||
                        $part6 == '1 Concerto' ||
                        $part6 == '07 Ritornello' ||
                        $part6 == '01 Ouvertüre' ||
                        $part6 == '01 Marche'
                    ) {
                        $part5 = $part6;
                        $part6 = $opus->getTitle();
                    }

                    $part6 = trim($part6, '"');
                    $aStr = explode(',',$part6);
                    $part6 = implode(", ",$aStr);

                    $part5 = trim($part5, "0..9");




                    $part = new Part();
                    $part->setPartnumber($bach->getPart());
                    $part->setTitle($part6);
                    $part->setParttype($part5);
                    $part->setOpus($opus);

                    $em->persist($part);
                }
            }

            $em->flush();

            return new Response('part for Cantate done');

        } catch (ORMException $e) {
            $logger = $this->get('logger');
            $logger->error($e->getMessage());

            return new Response('Error: '.$e->getMessage());
        }
    }

    /**
     * @Route("/buildaudio")
     *
     * @return Response
     */
    public function buildAudioAction()
    {
        $em = $this->getDoctrine()
                   ->getManager();

        $recs = $this->getDoctrine()
                     ->getRepository('AppBundle:Part')
                     ->getPartsJoined();


        foreach ($recs as $rec) {
            $at = new Audiotrack();

            $at->setAlbum($rec['album']);
            $at->setConductor($rec['conductor']);
            $at->setEnsemble($rec['ensemble']);
            $at->setPerformer($rec['performer']);
            $at->setRecordingYear($rec['date']);
            $at->setTrack($rec['track']);
            $at->setTrackType('local cd');
            $at->setPart($rec[0]);

            $em->persist($at);

        }
        $em->flush();

        return new Response('audio for Cantate done');
    }
}
