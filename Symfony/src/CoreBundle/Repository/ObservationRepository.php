<?php

namespace CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * ObservationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ObservationRepository extends EntityRepository
{
    public function findByIdUserWithSpecies($userId)
    {
        $qb = $this->createQueryBuilder('o');

        $qb
            ->where('o.user = :userId')
                ->setParameter('userId', $userId)
            ->leftJoin('o.bird', 's')
            ->addSelect('s');

        return $qb
            ->getQuery()
            ->getResult();
    }
}
