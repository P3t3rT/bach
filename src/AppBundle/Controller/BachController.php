<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Response;

class BachController extends Controller
{
    /**
     * @Route("/list", name="bach_list", options={"expose"=true})
     *
     */
    public function listAction()
    {
        try {
            $themes = $this->getDoctrine()
                           ->getRepository('AppBundle:Theme')
                           ->findAll();

            return $this->render('::list.html.twig', array('themes' => $themes));

        } catch (ORMException $e) {
            $logger = $this->get('logger');
            $logger->error($e->getMessage());
        }
    }

    /**
     * @Route("/table", name="bach_table", options={"expose"=true})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function tableAction(Request $request)
    {
        $records = array();
        $req = $request->request->all();
        //paging
        $aParams['start']  = intval($req['start']);
        $aParams['length'] = intval($req['length']);
        //sort
        $aParams['column'] = $req['order'][0]['column'];
        $aParams['dir']    = $req['order'][0]['dir'];
        //select
        if (isset($req['action']) && $req['action'] == 'filter') {
            $aParams['date_from'] = $req['date_from'];
            $aParams['date_to']   = $req['date_to'];
            $aParams['opus']      = filter_var(trim($req['opus']), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
            $aParams['title']     = filter_var(trim($req['title']), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
            $aParams['theme']     = intval($req['theme']);
        } else {
            $aParams['date_from'] = '';
            $aParams['date_to']   = '';
            $aParams['opus']      = '';
            $aParams['title']     = '';
            $aParams['theme']     = '';
        }
        try {
            $repo = $this->getDoctrine()
                         ->getRepository('AppBundle:Opus');

            $cnt =  $repo->countSelectedRecords($aParams);

            //make sure the query limit works correct when 'All' records selected
            $req['length'] < 0 ?  $aParams['length'] = $cnt : null;

            $recs = $repo->findSelectedRecords($aParams);

            //paging
            $iTotalRecords   = intval($cnt);
            $iDisplayLength  = intval($req['length']);
            $iDisplayLength  = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
            $iDisplayStart   = intval($req['start']);
            $records         = array();
            $records["data"] = array();
            $end             = $iDisplayStart + $iDisplayLength;
            $end             = $end > $iTotalRecords ? $iTotalRecords : $end;

            foreach ($recs as $rec) {

                $opus['id'] = $rec->getId();
                $opus['opus'] = $rec->getOpus();
                $opus['title'] = $rec->getTitle();
                $opus['description'] = $rec->getTheme()->getDescription();
                $opus['date'] = $rec->getDateFirstPerformance();

                $records["data"][] = explode('!!', ($this->render('::list_line.html.twig', array('opus' => $opus))->getContent()));
            }

            $records["draw"]            = intval($req['draw']);
            $records["recordsTotal"]    = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;

        } catch (ORMException $e) {
            $logger = $this->get('logger');
            $logger->error($e->getMessage());
//todo: proper error messag to screen
//            return new Response('Error: '.$e->getMessage());
        }

        return new JsonResponse($records);
    }

    /**
     * @Route("/parts/{opus}", name="bach_parts", options={"expose"=true}, requirements={"opus" = "\d+"}, defaults={"opus" = 1})
     *
     * @param Request $request
     * @return Response
     */
    public function partAction(Request $request)
    {
        $opusId= $request->get('opus');
        $data = array();

        try {
            $opus = $this->getDoctrine()
                         ->getRepository('AppBundle:Opus')
                         ->findOpus($opusId);

        } catch (ORMException $e) {
            $logger = $this->get('logger');
            $logger->error($e->getMessage());

            $this->get('session')->getFlashBag()->set('danger', 'Requested parts not found');
            return $this->redirectToRoute('bach_list');
        }

        $opus ? $head['opus'] = $opus->getOpus() : $head['opus'] = 'Unknown';
        $opus ? $head['title'] = $opus->getTitle() : $head['title'] = 'Unknown';

        try {
            $parts = $this->getDoctrine()
                          ->getRepository('AppBundle:Part')
                          ->findBy(array('opus' => $opusId), array('partnumber' => 'ASC'));

        } catch (ORMException $e) {
            $logger = $this->get('logger');
            $logger->error($e->getMessage());
            //todo: proper error messag to screen
            //            return new Response('Error: '.$e->getMessage());
        }

        if ($parts) {
            $i=0;
            foreach ($parts as $part) {
                $data[$i]['partid']     = $part->getId();
                $data[$i]['partnumber'] = $part->getPartnumber();
                $data[$i]['title']      = $part->getTitle();
                $data[$i]['parttype']   = $part->getParttype();
                $i++;
            }
        }

        return $this->render('::parts.html.twig', array('parts' => $data, 'head' => $head));
    }

    /**
     * @Route("/audio/{partid}", name="bach_audio", options={"expose"=true}, requirements={"partid" = "\d+"}, defaults={"partid" = 1})
     *
     * @param Request $request
     * @return Response
     */
    public function audioAction(Request $request)
    {
        $partId= $request->get('partid');
        $data = array();

        try {
            $part = $this->getDoctrine()
                         ->getRepository('AppBundle:Part')
                         ->find($partId);

        } catch (ORMException $e) {
            $logger = $this->get('logger');
            $logger->error($e->getMessage());
            //todo: proper error messag to screen
            //            return new Response('Error: '.$e->getMessage());
        }

        $part ? $head['title'] = $part->getTitle() : $head['title'] = 'Unknown';
        $part ? $head['partnumber'] = $part->getPartnumber() : $head['partnumber'] = '';

        try {
            $opus = $this->getDoctrine()
                         ->getRepository('AppBundle:Opus')
                         ->find($part->getOpus());

        } catch (ORMException $e) {
            $logger = $this->get('logger');
            $logger->error($e->getMessage());
            //todo: proper error messag to screen
            //            return new Response('Error: '.$e->getMessage());
        }

        $opus ? $head['opus'] = $opus->getOpus() : $head['opus'] = 'Unknown';

        try {
            $track = $this->getDoctrine()
                          ->getRepository('AppBundle:Audiotrack')
                          ->findOneBy(array('part' => $partId));

        } catch (ORMException $e) {
            $logger = $this->get('logger');
            $logger->error($e->getMessage());
            //todo: proper error messag to screen
            //            return new Response('Error: '.$e->getMessage());
        }

        $data['Album']          = $track->getAlbum();
        $data['Track']          = $track->getTrack();
        $data['Dirigent']       = $track->getConductor();
        $data['Ensemble']       = $track->getEnsemble();
        $data['Uitvoerende(n)'] = $track->getPerformer();

        return $this->render('::audio.html.twig', array('data' => $data, 'head' => $head));
    }
}
