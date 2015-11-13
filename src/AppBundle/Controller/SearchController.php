<?php

namespace AppBundle\Controller;

use Elastica\Query;
use Elastica\Query\Bool;
use Elastica\Query\Wildcard;
use Elastica\Query\QueryString;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @Route("/search", name="bach_search")
     *
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

        $rows = $this->_processResults();

        return $this->render('search_results.html.twig', array('rows' => $rows));

    /*ES bool query:
        {
          "query": {
            "bool": {
              "must": [
                {
                  "query_string": {
                    "query": "term",
                    "default_field": "_all"
                  }
                }
              ]
            }
          },
          "highlight": {
            "fields": {
              "*": {}
            }
          }
        }
    */

    }

    protected function _processResults()
    {
        $rows = array();
        $i=0;
        foreach ($this->results as $result) {
           $this->transformed = $result->getTransformed();
           $this->hit = $result->getResult()->getHit();
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



//           $rows[$i]['type'] = $hit['_type'];
//           foreach ($hit['highlight'] as $field => $highlights) {
//               $tmp1 = $field;
//               foreach ($highlights as $highlight) {
//                   $rows[$i]['highlight'] = $highlight;
//               }
//           }
        $i++;
        }

        return $rows;
    }

    protected function getOpusData($i)
    {
        $rows[$i]['opus']['id'] = $this->transformed->getId();
        $rows[$i]['opus']['opus'] = $this->transformed->getOpus();
        $rows[$i]['opus']['title'] = $this->hit['highlight']['opus_title'][0];
    }

    protected function getPartData($i)
    {

    }

    protected function getAudiotrackData($i)
    {

    }
}