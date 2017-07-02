<?php

namespace CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * SpeciesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SpeciesRepository extends EntityRepository
{
    /**
     * @param string $bird
     *
     * @return array
     */
    public function findLike($bird)
    {
        return $this
            ->createQueryBuilder('a')
            ->where('a.nomVern LIKE :nomVern')
            ->setParameter('nomVern', "$bird%")
            ->orderBy('a.nomVern')
            ->setMaxResults(10)
            ->getQuery()
            ->execute()
            ;
    }

    public function getLikeQueryBuilder($pattern)
    {
        return $this
            ->createQueryBuilder('c')
            ->where('c.nomVern LIKE :pattern')
            ->setParameter('pattern', $pattern)
            ;
    }
}
