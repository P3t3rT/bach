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
     * @Route("/convert")
     *
     */
    public function convertAction()
    {
        return $this->render('::blank_page.html.twig');
    }

//    /**
//     * @Route("/bachtoopus")
//     *
//     * @return \Symfony\Component\HttpFoundation\Response
//     */
//    public function bachToOpusAction()
//    {
//        try {
//            $recs = $this->getDoctrine()
//                         ->getRepository('AppBundle:Bach')
//                         ->findTitles();
//
//            $em = $this->getDoctrine()->getManager();
//
//            foreach ($recs as $rec) {
////                $opus = new Opus();
//
//                $pos = strpos($rec->getTitle(), ' ');
//                $part1 = trim(substr($rec->getTitle(),0,$pos));
//                $part2 = trim(substr($rec->getTitle(),$pos));
//                if (strlen($part2) > 63) {
//                   // echo $part1 . ' ' . strlen($part2) . ' ' . $part2 . "<br>";
//                    $repo = $em->getRepository('AppBundle:Opus');
//                    $rec = $repo->findOneBy(array('opus' => $part1));
//
//                    $rec->setTitle($part2);
//                    //$em->persist($rec);
//
//                }
//            }
//
//            $em->flush();
//
//            return new Response('bachtoopus done');
//
//        } catch (ORMException $e) {
//            $logger = $this->get('logger');
//            $logger->error($e->getMessage());
//
//            return new Response('Error: '.$e->getMessage());
//        }
//    }

//    /**
//     * @Route("/opuscantate")
//     *
//     * @return Response
//     */
//    public function opusCantateAction()
//    {
//        try {
//
//            $em = $this->getDoctrine()->getManager();
//            $recs = $em->getRepository('AppBundle:Opus')
//                       ->findCantates();
//
//            foreach ($recs as $rec) {
//                $aStr = array();
//                $aStr = explode(',',$rec->getTitle());
//
//                if ($aStr) {
//                    $part = implode(", ",$aStr);
//                    $rec->setTitle($part);
//                }
//            }
//
//            $em->flush();
//
//            return new Response('opusCantate done');
//
//        } catch (ORMException $e) {
//            $logger = $this->get('logger');
//            $logger->error($e->getMessage());
//
//            return new Response('Error: '.$e->getMessage());
//        }
//    }

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
                       ->findTheme(7);

            foreach ($recs as $rec) {
                $aStr = array();
                $aStr = explode(',',$rec->getTitle());

                if ($aStr) {
                    $part = implode(", ",$aStr);
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
                           ->findTheme(7);

            foreach ($opusrecs as $opus) {
                $bachrecs = $em->getRepository('AppBundle:Bach')
                               ->findParts($opus->getOpus());
                foreach ($bachrecs as $bach) {
                    $pos = strpos($bach->getTitle(), ' ');
                    $part1 = trim(substr($bach->getTitle(),0,$pos));
//                    if ($part1 <> 'BWV0249') {
//                        continue;
//                    }
                    $part2 = trim(substr($bach->getTitle(),$pos));
                    $part3 = trim(str_replace('Sacred Song','',$part2));
//                    $part4 = trim(trim($part3, "0..9"));
//                    $part4 = trim(trim($part4, "0..9"));

                    $pos = strpos($part3, '"');
                    $part5 = trim(substr($part3,0,$pos));
//                    $part4= trim(substr($part4,$pos));
//
                    $pos = strpos($part3, '"');
//                    $part5 = substr($part4,0,$pos);
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

//            $em->flush();

            return new Response('part for Organ done');

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

        return new Response('audio for Organ done');
    }

    /**
    * @Route("/clean")
    *
    * @return Response
    */
   public function cleanAudioAction()
   {
       $em = $this->getDoctrine()
                  ->getManager();

       $recs = $this->getDoctrine()
                    ->getRepository('AppBundle:Audiotrack')
                    ->findAll();

       foreach ($recs as $rec) {
           $arr = explode('-', $rec->getAlbum());
           $album = trim($arr[0]). ' - ' .trim($arr[1]);

           $rec->setAlbum($album);
       }

       $em->flush();

       return new Response('cleaning for Track done');
   }

    /**
      * @Route("/resequence")
      *
      * @return Response
      */
     public function resequenceAction()
     {
         $em = $this->getDoctrine()
                    ->getManager();
         $recs = $this->getDoctrine()
                      ->getRepository('AppBundle:Bach')
                      ->find248();
         $i = 0;
         foreach ($recs as $rec) {
            $i++;
            $rec->setPart($i);
        }
        $em->flush();
         return new Response('done');
     }

}
