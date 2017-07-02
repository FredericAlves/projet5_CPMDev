<?php

namespace CoreBundle\Repository;
use CoreBundle\Entity\Observation;

/**
 * ObservationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ObservationRepository extends \Doctrine\ORM\EntityRepository
{
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
}
