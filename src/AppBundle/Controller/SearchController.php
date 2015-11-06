<?php

namespace AppBundle\Controller;

use Elastica\Query;
use Elastica\Query\Bool;
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
            "fields" => array("*" => new stdClass)
        ));

        // Returns a mixed array of any objects mapped + highlights
        $results = $finder->findHybrid($query);

        return new Response('done');
    }
}