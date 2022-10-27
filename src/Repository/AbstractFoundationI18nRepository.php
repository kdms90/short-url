<?php

namespace App\Repository;

use App\Entity\AbstractFoundationI18n;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AbstractFoundationI18n|null find($id, $lockMode = null, $lockVersion = null)
 * @method AbstractFoundationI18n|null findOneBy(array $criteria, array $orderBy = null)
 * @method AbstractFoundationI18n[]    findAll()
 * @method AbstractFoundationI18n[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbstractFoundationI18nRepository extends ServiceEntityRepository
{
    /**
     * AbstractFoundationI18nRepository constructor.
     * @param \Doctrine\Persistence\ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AbstractFoundationI18n::class);
    }
}
