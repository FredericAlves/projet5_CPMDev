<?php

namespace CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use CoreBundle\Entity\Observation;

/**
 * ObservationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ObservationRepository extends EntityRepository
{
    /**
     * Method to get all observations (for a user who is logged)
     * @param $userId
     * @return array
     */
    public function findByIdUserWithSpecies($userId)
    {
        $qb = $this->createQueryBuilder('o');

        $qb
            ->where('o.user = :userId')
                ->setParameter('userId', $userId)
            ->orderBy('o.date', 'desc')
            ->leftJoin('o.bird', 's')
            ->addSelect('s')
            ->leftJoin('o.picture', 'p')
            ->addSelect('p');

        return $qb
            ->getQuery()
            ->getResult();
    }

    public function findLastObservations(){
        $qd = $this->createQueryBuilder('o');
        $statut = 'accepted';

        $qd
            ->where('o.statut = :statut')
            ->setParameter('statut',$statut)
            ->orderBy('o.date', 'desc')
            ->setMaxResults(3);

        return $qd
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Observation $observation
     * @param bool $flush
     */
    public function add(Observation $observation, $flush = true)
    {

        $this->getEntityManager()->persist($observation);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Method to get all observations by species
     * @param $birdId
     * @return array
     */
    public function findWithBirdName($birdId, $statut){
        $qb = $this->createQueryBuilder('o');

        $qb ->where('o.bird = :birdId')
            ->setParameter('birdId', $birdId)
            ->andWhere('o.statut = :statut')
            ->setParameter('statut', $statut)
            ->leftJoin('o.bird', 's')
            ->addSelect('s')
            ->leftJoin('o.user', 'u')
            ->addSelect('u')
            ->leftJoin('o.picture', 'p')
            ->addSelect('p')
        ;

        return $qb
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * Method to get all amateur observations to validate
     * @return QueryBuilder
     */
    public function findObservationsToValidate(){
        $qb = $this->createQueryBuilder('o');

        $toValidate = "untreated";

        $qb
            ->where('o.statut = :toValidate')
            ->setParameter('toValidate', $toValidate)
            ->orderBy('o.date', 'desc')
        ;

        return $qb->getQuery()->getResult();
    }

    /**
     * Method to delete an observation
     * @param $observationId
     */
    public function deleteAnObservation($observationId){
        $qb = $this->createQueryBuilder('o');
        $qb
            ->delete()
            ->where('o.id = :observationId')
            ->setParameter('observationId', $observationId);

        $qb->getQuery()->getResult();
    }
}
