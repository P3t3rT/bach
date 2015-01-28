<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\ORMException;

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
//            $this->get('session')->getFlashBag()->set('danger',
//            $this->get('translator')->trans('Admin.list.error'));
//            return $this->render('ADHVdsBundle:Administrator:dashboard.html.twig');
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
                $records["data"][] = array(
                    '<input type="checkbox" name="id[]" value="' . $rec->getId() . '">',
                    $rec->getOpus(),
                    $rec->getTitle(),
                    $rec->getTheme()->getDescription(),
                    $rec->getDateFirstPerformance(),
                    '<a href="javascript:;" class="btn btn-xs default"><i class="fa fa-plus-square-o"></i> Deel</a>'.
                    '<a href="javascript:;" class="btn btn-xs default"><i class="fa fa-file-text-o"></i> Tekst</a>',
                );
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
}

