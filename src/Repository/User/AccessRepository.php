<?php

namespace App\Repository\User;

use App\Entity\User\Access;
use App\Repository\AbstractFoundationRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Access|null find($id, $lockMode = null, $lockVersion = null)
 * @method Access|null findOneBy(array $criteria, array $orderBy = null)
 * @method Access[]    findAll()
 * @method Access[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessRepository extends AbstractFoundationRepository
{
    /**
     * AccessRepository constructor.
     * @param \Doctrine\Persistence\ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Access::class);
    }
}
