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
        return $this->render('::list.html.twig');
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

        $req     = $request->request->all();

        //$aParams = array();
        try {
            $em = $this->getDoctrine()
                       ->getManager();

            $cnt =  $em->getRepository('AppBundle:Opus')
                       ->countRecords();
            //paging
            $iTotalRecords   = intval($cnt);
            $iDisplayLength  = intval($req['length']);
            $iDisplayLength  = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
            $iDisplayStart   = intval($req['start']);
            $sEcho           = intval($req['draw']);
            $records         = array();
            $records["data"] = array();
            $end             = $iDisplayStart + $iDisplayLength;
            $end             = $end > $iTotalRecords ? $iTotalRecords : $end;

            //paging
            //todo: make themesearch a selectbox and exclude from sorting
            $aParams['start']  = $iDisplayStart;
            $aParams['length'] = $iDisplayLength;
            //sort
            $aParams['column'] = $req['order'][0]['column'];
            $aParams['dir']    = $req['order'][0]['dir'];
            //select
            if (isset($req['action']) && $req['action'] == 'filter') {
                $aParams['date_from'] = $req['date_from'];
                $aParams['date_to']   = $req['date_to'];
                $aParams['opus']      = filter_var(trim($req['opus']), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
                $aParams['title']     = filter_var(trim($req['title']), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
                $aParams['theme']     = filter_var(trim($req['theme']), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
            } else {
                $aParams['date_from'] = '';
                $aParams['date_to']   = '';
                $aParams['opus']      = '';
                $aParams['title']     = '';
                $aParams['theme']     = '';
            }

            $recs = $em->getRepository('AppBundle:Opus')
                       ->findSelectedRecords($aParams);

            foreach ($recs as $rec) {
                $records["data"][] = array(
                    '<input type="checkbox" name="id[]" value="' . $rec->getId() . '">',
                    $rec->getOpus(),
                    $rec->getTitle(),
                    $rec->getTheme()->getDescription(),
                    $rec->getDateFirstPerformance(),
                    '<a href="javascript:;" class="btn btn-xs default"><i class="fa fa-search"></i> View</a>',
                );
            }

            $records["draw"]            = $sEcho;
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


//        $iTotalRecords = 178;
//        $iDisplayLength = intval($_REQUEST['length']);
//        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
//        $iDisplayStart = intval($_REQUEST['start']);
//        $sEcho = intval($_REQUEST['draw']);
//
//        $records = array();
//        $records["data"] = array();
//
//        $end = $iDisplayStart + $iDisplayLength;
//        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

//        $status_list = array(
//        array("success" => "Pending"),
//        array("info" => "Closed"),
//        array("danger" => "On Hold"),
//        array("warning" => "Fraud")
//        );

//        for($i = $iDisplayStart; $i < $end; $i++) {
//        $status = $status_list[rand(0, 2)];
//        $id = ($i + 1);
//        $records["data"][] = array(
//          '<input type="checkbox" name="id[]" value="'.$id.'">',
//          $id,
//          '12/09/2013',
//          'Jhon Doe',
//          'Jhon Doe',
////          '450.60$',
////          rand(1, 10),
////          '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>',
//          '<a href="javascript:;" class="btn btn-xs default"><i class="fa fa-search"></i> View</a>',
//        );
//        }

//        if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
//        $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
//        $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
//        }

//        $records["draw"] = $sEcho;
//        $records["recordsTotal"] = $iTotalRecords;
//        $records["recordsFiltered"] = $iTotalRecords;

        //echo json_encode($records);

//        return new JsonResponse($records);
//
//    }
//}

