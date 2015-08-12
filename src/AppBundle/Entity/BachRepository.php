<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * BachRepository
 */
class BachRepository extends EntityRepository
{
    public function findParts($opus, $length)
    {
        $query = $this->_em->createQueryBuilder()
               ->select('b')
               ->from('AppBundle:Bach','b')
               ->where("SUBSTRING(b.title,1,$length) = '$opus'")
               ->andWhere("SUBSTRING(b.title,$length+1,1) = ' '")
               ->getQuery();

        return $query->getResult();
    }

}
