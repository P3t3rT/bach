<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * OpusRepository
 */
class OpusRepository extends EntityRepository
{
    /**
     * Find the selected Opus records
     *
     * @param $aParams
     * @return array
     */
    public function findSelectedRecords($aParams)
    {

        //column mapping for ordering
        $orderMapping = array('o.id','o.opus','o.title','o.theme','o.dateFirstPerformance');
        $column = $orderMapping[$aParams['column']];
        $dir    = $aParams['dir'];

        $qb = $this->_em->createQueryBuilder();
        $qb->select('o')
           ->from('AppBundle:Opus', 'o')
           ->where('1=1')
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

        if (!empty($aParams['theme'])) {
            $qb->andWhere('o.theme = :theme')
               ->setParameter('theme', $aParams['theme']);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Count all Opus records
     *
     * @param $aParams
     * @return mixed
     */
    public function countSelectedRecords($aParams)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('count(o.id)')
           ->from('AppBundle:Opus', 'o')
           ->where('1=1');

        if (!empty($aParams['opus'])) {
            $qb->andWhere('o.opus LIKE :opus')
               ->setParameter('opus', '%' . $aParams['opus'] . '%');
        }

        if (!empty($aParams['title'])) {
            $qb->andWhere('o.title LIKE :title')
               ->setParameter('title', '%' . $aParams['title'] . '%');
        }

        if (!empty($aParams['theme'])) {
            $qb->andWhere('o.theme = :theme')
               ->setParameter('theme', $aParams['theme']);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findOpus($id)
    {
        $query = $this->_em->createQuery(
            'SELECT o
            FROM AppBundle:Opus o
            WHERE o.id like :id'
        )->setParameter('id', $id);

        return $query->getSingleResult();
    }

}
