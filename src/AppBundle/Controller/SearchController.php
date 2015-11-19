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
//        $rows = array();
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

        //return $rows;
    }

    protected function getOpusData($i)
    {
//        $rows = array();
        $this->rows[$i]['opus']['id'] = $this->transformed->getId();
        $this->rows[$i]['opus']['opus'] = $this->transformed->getOpus();
        $this->rows[$i]['opus']['opus_title'] = $this->hit['highlight']['opus_title'][0];
    }

    protected function getPartData($i)
    {
        foreach ($this->hit['highlight'] as $field => $highlights) {
            $this->rows[$i]['part'][$field] = $this->hit['highlight'][$field][0];
            $this->rows[$i]['part']['parttype'] = $this->transformed->getParttype();
        }

        //zoek bijbehorend opus record
        //bestaat opus row al? Voeg toe aan bestaand record, anders nieuw record
        $this->rows[$i]['opus']['id'] = $this->transformed->getOpus()->getId();
        $this->rows[$i]['opus']['opus'] = $this->transformed->getOpus()->getOpus();
        $this->rows[$i]['opus']['opus_title'] = $this->transformed->getOpus()->getTitle();
    }

    protected function getAudiotrackData($i)
    {
        foreach ($this->hit['highlight'] as $field => $highlights) {
           $this->rows[$i]['track'][$field] = $this->hit['highlight'][$field][0];
           $field != 'conductor' ? $this->rows[$i]['track']['conductor'] = $this->transformed->getConductor() : null;
           $field != 'ensemble' ? $this->rows[$i]['track']['ensemble'] = $this->transformed->getEnsemble() : null;
           $field != 'performer' ? $this->rows[$i]['track']['performer'] = $this->transformed->getPerformer(): null;
       }
    }
}

//           $rows[$i]['type'] = $hit['_type'];
//           foreach ($hit['highlight'] as $field => $highlights) {
//               $tmp1 = $field;
//               foreach ($highlights as $highlight) {
//                   $rows[$i]['highlight'] = $highlight;
//               }
//           }