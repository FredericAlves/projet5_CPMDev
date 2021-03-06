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
            ->setParameter('nomVern', "%$bird%")
            ->orderBy('a.nomVern')
            ->setMaxResults(20)
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

    /**
     * Method to get all ordre from Species
     * @return array
     */
    public function getOrdre()
    {
        $qb = $this->createQueryBuilder('s');

        $qb->select('s.ordre')
            ->where('s.nomVern != :notnull')
            ->setParameter('notnull', '')
            ->distinct(true)
        ;

        return $qb
            ->getQuery()
            ->getResult();
    }

    /**
     * Method to get famille from Species
     * @return array
     */
    public function getFamille()
    {
        $qb = $this->createQueryBuilder('s');

        $qb->select('s.famille')
            ->distinct(true)
        ;

        return $qb
            ->getQuery()
            ->getResult();
    }

    /**
     * Method to get all birds from Species
     * @return array
     */
    public function getBirds()
    {
        $qb = $this->createQueryBuilder('s');

        $qb->select('s.nomVern', 's.id', 's.url')
            ->distinct('s.nomVern')
        ;

        return $qb
            ->getQuery()
            ->getResult();
    }

    /**
     * Method to get birds from Species by family
     * @param $family
     * @return array
     */
    public function getBirdsByFamily($family)
    {
        $qb = $this->createQueryBuilder('s');

        $qb->select('s.nomVern', 's.id', 's.url')
            ->where('s.famille = :family')
            ->setParameter('family', $family)
            ->distinct(true)
        ;

        return $qb
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * Method to get families from Species by order
     * @param $order
     * @return array
     */
    public function getFamilyByOrder($order)
    {
        $qb = $this->createQueryBuilder('s');

        $qb->select('s.famille')
            ->where('s.ordre = :order')
            ->setParameter('order', $order)
            ->distinct(true)
        ;

        return $qb
            ->getQuery()
            ->getArrayResult();
    }
}
