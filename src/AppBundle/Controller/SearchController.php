<?php

namespace AppBundle\Controller;

use Elastica\Query;
use Elastica\Query\Bool;
use Elastica\Query\QueryString;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
    /**
     * @var Raw search results
     */
    private $results;
    /**
     * @var Raw search results transformed
     */
    private $transformed;
    /**
     * @var hit data from search result
     */
    private $hit;
    /**
     * @var array Result for output
     */
    private $rows = array();

    /**
     * @Route("/search", name="bach_search")
     *
     * ES bool query:
     *  {
     *    "query": {
     *        "bool": {
     *          "must": [
     *            {
     *              "query_string": {
     *                "query": "term",
     *                "default_field": "_all"
     *              }
     *            }
     *          ]
     *        }
     *      },
     *      "highlight": {
     *        "fields": {
     *         "*": {}
     *        }
     *      }
     *    }
     */
    public function searchAction(Request $request)
    {
        $term = $request->isMethod('GET') ? $request->get('query') : $request->request->get('query');

        //todo: validate request data

        $finder = $this->container->get('fos_elastica.finder.bach');

        $boolQuery = new Bool();

        $queryString = new QueryString();
        $queryString->setDefaultField('_all');
        $queryString->setQuery($term);
        $boolQuery->addMust($queryString);

        $query = new Query($boolQuery);

        $query->setHighlight(array(
            "fields" => array("*" => new stdClass),
            "pre_tags" => ["<strong>"],
            "post_tags" => ["</strong>"],
        ));

        // Returns a mixed array of any objects mapped + highlights
        $this->results = $finder->findHybrid($query);

        $this->_processResults();

        return $this->render('search_results.html.twig', array('rows' => $this->rows));
    }

    protected function _processResults()
    {
        $i = 0;
        foreach ($this->results as $result) {
            $this->transformed = $result->getTransformed();
            $this->hit         = $result->getResult()->getHit();
            switch ($this->hit['_type']) {
                case "opus":
                    $this->getOpusData($i);
                    break;
                case "part":
                    $this->getPartData($i);
                    break;
                case "audiotrack":
                    $this->getAudiotrackData($i);
            }
            $i++;
        }
    }

    protected function getOpusData($i)
    {
        $this->rows[$i]['opus']['id'] = $this->transformed->getId();
        $this->rows[$i]['opus']['opus'] = $this->transformed->getOpus();
        $this->rows[$i]['opus']['opus_title'] = $this->hit['highlight']['opus_title'][0];
    }

    protected function getPartData($i)
    {
        //zoek bijbehorend opus row
        //todo: bestaat opus row al? Voeg toe aan bestaande row, anders nieuwe row
        $this->rows[$i]['opus']['id'] = $this->transformed->getOpus()->getId();
        $this->rows[$i]['opus']['opus'] = $this->transformed->getOpus()->getOpus();
        $this->rows[$i]['opus']['opus_title'] = $this->transformed->getOpus()->getTitle();

        foreach ($this->hit['highlight'] as $field => $highlights) {
            $this->rows[$i]['part'][$field] = $this->hit['highlight'][$field][0];
            $field != 'parttype' ? $this->rows[$i]['part']['parttype'] = $this->transformed->getParttype() : null;
            $field != 'partnumber' ?  $this->rows[$i]['part']['partnumber'] = $this->transformed->getPartnumber() : null;
            $field != 'part_title' ? $this->rows[$i]['part']['part_title'] = $this->transformed->getTitle() : null;
        }
    }

    protected function getAudiotrackData($i)
    {
        //zoek bijbehorend opus row
        //todo: bestaat opus row al? Voeg toe aan bestaande row, anders nieuwe row
        $opus = $this->getDoctrine()
                     ->getRepository('AppBundle:Opus')
                     ->find($this->transformed->getPart()->getOpus());

        $this->rows[$i]['opus']['id'] = $opus->getId();
        $this->rows[$i]['opus']['opus'] = $opus->getOpus();
        $this->rows[$i]['opus']['opus_title'] = $opus->getTitle();

        //zoek bijbehorend part row
        $this->rows[$i]['part']['part_title'] = $this->transformed->getPart()->getTitle();
        $this->rows[$i]['part']['parttype'] = $this->transformed->getPart()->getParttype();
        $this->rows[$i]['part']['partnumber'] = $this->transformed->getPart()->getPartnumber();

        //create track rows
        foreach ($this->hit['highlight'] as $field => $highlights) {
            $this->rows[$i]['track'][$field] = $this->hit['highlight'][$field][0];
            $field != 'conductor' ? $this->rows[$i]['track']['conductor'] = $this->transformed->getConductor() : null;
            $field != 'ensemble'  ? $this->rows[$i]['track']['ensemble']  = $this->transformed->getEnsemble() : null;
            $field != 'performer' ? $this->rows[$i]['track']['performer'] = $this->transformed->getPerformer() : null;
        }
    }
}
