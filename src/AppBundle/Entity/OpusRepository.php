<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * OpusRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OpusRepository extends EntityRepository
{
    public function findCantates()
    {
        $query = $this->_em->createQueryBuilder()
                           ->select('c')
                           ->from('AppBundle:Opus', 'c')
                           ->where('c.theme = 1')//               ->setParameter('id', $id)
                           ->getQuery()
                           ->getResult();

        return $query;
    }

    public function findSelectedRecords($aParams)
    {

        //column mapping for ordering
        $orderMapping = array('o.id','o.opus','o.title','o.theme','o.dateFirstPerformance');
        $column = $orderMapping[$aParams['column']];
        $dir    = $aParams['dir'];

        $qb = $this->_em->createQueryBuilder('o');
        $qb->select('o')
           ->from('AppBundle:Opus', 'o')
           ->where('1=1')//               ->setParameter('id', $id)
           ->orderBy($column, $dir)
           ->setFirstResult($aParams['start'])
           ->setMaxResults($aParams['length']);

        if (!empty($aParams['opus'])) {
            $qb->andWhere('o.opus LIKE :opus')
               ->setParameter('opus', '%' . $aParams['opus'] . '%');
        }

        if (!empty($aParams['title'])) {
                    $qb->andWhere('o.title LIKE :title')
                       ->setParameter('title', '%' . $aParams['title'] . '%');
                }

        return $qb->getQuery()->getResult();
    }

    public function countRecords()
    {
        return $this->_em->createQueryBuilder()
                         ->select('count(o.id)')
                         ->from('AppBundle:Opus','o')
                         ->getQuery()
                         ->getSingleScalarResult();
    }

}