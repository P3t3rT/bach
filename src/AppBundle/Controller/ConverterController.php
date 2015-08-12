<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Audiotrack;
use AppBundle\Entity\Part;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class ConverterController extends Controller
{
    /**
     * @Route("/opustheme")
     *
     * @return Response
     */
    public function opusThemeAction()
    {
        try {

            $em = $this->getDoctrine()->getManager();
            $recs = $em->getRepository('AppBundle:Opus')
                       ->findTheme(12);

            foreach ($recs as $rec) {
                $aStr = array();
                $aStr = explode(',',$rec->getTitle());

                if ($aStr) {
                    $part = implode(", ",$aStr);
//                    $part2 = trim(str_replace('1 Allegro','',$part));
//                    $pos = strpos($part, ' ');
//
//                    $part1 = trim(substr($part,0,$pos));
//                    $part2 = trim(substr($part,$pos));
//                    $pos = strpos($part2, ' ');
//
//                    $part3 = trim(substr($part2,0,$pos));
//                    $part4 = trim(substr($part2,$pos));
//
//                    $part5 = trim($part4, '"');

                    $rec->setTitle($part);
                }
            }

//           $em->flush();

            return new Response('opusTheme done');

        } catch (ORMException $e) {
            $logger = $this->get('logger');
            $logger->error($e->getMessage());

            return new Response('Error: '.$e->getMessage());
        }
    }

    /**
     * @Route("/buildpartx")
     *
     * @return Response
     */
    public function buildPartAction($oneOpus)
    {
        try {
            $em = $this->getDoctrine()->getManager();

            $opusrecs = $em->getRepository('AppBundle:Opus')
                           ->findBy(array('opus' => $oneOpus));

            foreach ($opusrecs as $opus) {
                $bachrecs = $em->getRepository('AppBundle:Bach')
                               ->findParts($opus->getOpus(), strlen($oneOpus));
                foreach ($bachrecs as $bach) {
                    $pos = strpos($bach->getTitle(), ' ');
                    $part1 = trim(substr($bach->getTitle(),0,$pos));
                    $part2 = trim(substr($bach->getTitle(),$pos));
                    $part3 = trim(str_replace('Cantata','',$part2));
                    $part3 = trim(trim($part3, "0..9"));

                    $pos = strpos($part3, '"');
                    $part5 = trim(substr($part3,0,$pos));
                    $part5 = trim(str_replace('part','',$part5));
                    $part5 = trim(trim($part5, "0..9"));

                    $pos = strpos($part3, '"');
                    $part6= trim(substr($part3,$pos));

                    $part6 = trim($part6, '"');
                    $aStr = explode(',',$part6);
                    $part6 = implode(", ",$aStr);


                    $part = new Part();

                    $part->setPartnumber($bach->getPart());
                    $part->setTitle($part6);
                    $part->setParttype($part5);
                    $part->setOpus($opus);

                    $em->persist($part);
                }
            }


            return new Response('part for Overig done');

        } catch (ORMException $e) {
            $logger = $this->get('logger');
            $logger->error($e->getMessage());

            return new Response('Error: '.$e->getMessage());
        }
    }

    /**
     * @Route("/buildaudiox")
     *
     * @return Response
     */
    public function buildAudioAction($opus)
    {
        $em = $this->getDoctrine() ->getManager();

        $recs = $this->getDoctrine()
                     ->getRepository('AppBundle:Part')
                     ->getPartsJoined($opus, strlen($opus));

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

        return new Response('audio done');
    }

    /**
      * @Route("/rebuild")
      *
      * @return Response
      */
    public function rebuildAction()
    {
        $opusArray = (array(
//            'BWV0036',
//            'BWV0036c',
//            'BWV0063',
//            'BWV0063app',
//            'BWV0069',
//            'BWV0069a',
//            'BWV0134',
//            'BWV0134a',
//            'BWV0173',
//            'BWV0173a',
//            'BWV0182',
//            'BWV0182app',
//            'BWV0207',
//            'BWV0207a',

        ));

        $em = $this->getDoctrine()->getManager();

        foreach ($opusArray as $opus) {
            $parts = $em->getRepository('AppBundle:Part')->getPartsByOpus($opus);

            //remove old stuff (both parts and audiotracks)
            foreach ($parts as $part) {
                $em->remove($part);
            }
            $em->flush();

            //rebuild parts
            $this->buildPartAction($opus);
            $em->flush();

            //rebuild tracks
            $this->buildAudioAction($opus);
        }

        $em->flush();

        return new Response('rebuild done');
    }

}
