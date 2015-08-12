<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * PartRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PartRepository extends EntityRepository
{
    //todo: remove method when conversion done
    public function getPartsJoined($opus)
    {
        $query = $this->_em->createQuery(
            'SELECT p, o.opus, b.conductor, b.ensemble, b.performer, b.date, b.album, b.track
            FROM AppBundle:Part p
             LEFT JOIN AppBundle:Opus o
               WITH p.opus = o.id
            LEFT JOIN AppBundle:Bach b
               WITH SUBSTRING(b.title,1,8) = o.opus AND p.partnumber = b.part
            WHERE o.opus = :opus'
        )->setParameter('opus', $opus);

        return $query->getResult();
    }

    public function getPartsByOpus($opus)
    {
        $query = $this->_em->createQuery(
            'SELECT p
            FROM AppBundle:Part p
            LEFT JOIN AppBundle:Opus o
              WITH p.opus = o.id
            WHERE o.opus = :opus'
        )->setParameter('opus', $opus);

        return $query->getResult();
    }

}
