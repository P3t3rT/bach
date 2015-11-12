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
        $results = $finder->findHybrid($query);

        $rows = array();
        $i=0;
        foreach ($results as $result) {
            $hit = $result->getResult()->getHit();
            $rows[$i]['type'] = $hit['_type'];
            foreach ($hit['highlight'] as $field => $highlights) {
                $tmp1 = $field;
                foreach ($highlights as $highlight) {
                    $rows[$i]['highlight'] = $highlight;
                }
            }
        $i++;
        }

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
}