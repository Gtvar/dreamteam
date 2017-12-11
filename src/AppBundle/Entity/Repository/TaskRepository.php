<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * Class TaskRepository
 */
class TaskRepository extends EntityRepository
{
    /**
     * Get by user
     *
     * @param User $user
     *
     * @return Task[]
     */
    public function getByUser(User $user)
    {
        $qb = $this->createQueryBuilder('t')
            ->select('t', 'u')
            ->innerJoin('t.user', 'u')
            ->where('t.user = :user')
            ->setParameter('user', $user);

        return $qb->getQuery()->getResult();
    }
}