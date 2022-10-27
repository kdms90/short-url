<?php

namespace App\Repository;

use App\Entity\AbstractFoundation;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AbstractFoundationRepository|null find($id, $lockMode = null, $lockVersion = null)
 * @method AbstractFoundationRepository|null findOneBy(array $criteria, array $orderBy = null)
 * @method AbstractFoundationRepository[]    findAll()
 * @method AbstractFoundationRepository[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbstractFoundationRepository extends ServiceEntityRepository
{
    /**
     * AbstractFoundationRepository constructor.
     * @param \Doctrine\Persistence\ManagerRegistry $registry
     * @param string $entityClass
     */
    public function __construct(ManagerRegistry $registry, $entityClass = AbstractFoundation::class)
    {
        parent::__construct($registry, $entityClass);
    }

    /**
     * Custom findAll
     *
     * @return array
     */
    public function findAllOverride()
    {
        $query = $this->createQueryBuilder('entity')
            ->where('entity.deleted = 0')
            ->getQuery();

        return $query->getResult();
    }

    /**
     * Retrieve entities for pagination
     *
     * @param int $numberPerPage
     * @param int $page
     * @param string $query
     * @param $fromDate
     * @param $toDate
     * @param array $args
     * @return Paginator
     * @throws \Exception
     */
    public function retrieve(int $numberPerPage = null, int $page = null, string $query = null, \DateTime $fromDate = null, \DateTime $toDate = null, ...$args)
    {
        if ((int)$page < 1) {
            $page = 1;
        }
        if ((int)$numberPerPage < 1) {
            $numberPerPage = 20;
        }
        $qb = $this->createQueryBuilder('entity');
        $qb->where('entity.deleted = 0');

        if ($fromDate && $fromDate instanceof DateTime) {
            $qb->andWhere('entity.dateAdd >= :fromDate');
        }
        if ($toDate && $toDate instanceof DateTime) {
            $qb->andWhere('entity.dateAdd <= :toDate');
        }
        if ($fromDate && $fromDate instanceof DateTime)
            $qb->setParameter('fromDate', new DateTime($fromDate->format('Y-m-d') . " 00:00:00"));
        if ($toDate && $toDate instanceof DateTime)
            $qb->setParameter('toDate', new DateTime($toDate->format('Y-m-d') . ' 23:59:59'));

        $qb->orderBy('entity.dateAdd', 'DESC');
        $query = $qb->getQuery();
        $query->setFirstResult(($page - 1) * $numberPerPage)
            ->setMaxResults($numberPerPage);

        return new Paginator($query);
    }

    /**
     * @param bool $takeDeleted
     *
     * @return int
     */
    public function countAll(bool $takeDeleted = null)
    {
        $qb = $this->createQueryBuilder('entity');
        $qb->select('count(entity.id)');
        if (!$takeDeleted)
            $qb->where('entity.deleted = 0');

        try {
            return (int)$qb->getQuery()->getSingleScalarResult();
        } catch (NoResultException $e) {
            return 0;
        } catch (NonUniqueResultException $e) {
            return count($qb->getQuery()->getResult());
        }
    }

    /**
     * Renvoit l'élément précédent et suivant d'un entité.
     *
     * @param int $id
     * @param bool $company_id
     * @param array $args
     * @return array|int[]
     */
    public function getPreviousNext($id, $company_id = false, ...$args)
    {
        $expr       = $this->_em->getExpressionBuilder();
        $qbNext     = $this->createQueryBuilder('next')
            ->select(['MIN(next.id)'])
            ->where($expr->gt('next.id', ':id'));
        $qbPrevious = $this->createQueryBuilder('prev')
            ->select(['MAX(prev.id)'])
            ->where($expr->lt('prev.id', ':id'));
        if ($company_id) {
            $qbNext->andWhere('next.mainCompanyId = ' . (int)$company_id);
            $qbPrevious->andWhere('prev.mainCompanyId = ' . (int)$company_id);
        }
        $query = $this->createQueryBuilder('entity')
            ->select(['entity.id'])
            ->where($expr->orX(
                $expr->eq('entity.id', '(' . $qbNext->getDQL() . ')'),
                $expr->eq('entity.id', '(' . $qbPrevious->getDQL() . ')')
            ))
            ->setParameter('id', $id)
            ->addOrderBy('entity.id', 'ASC')
            ->getQuery();
        //optionally enable caching
        $query->useQueryCache(true)->enableResultCache(true, 3600);
        $results = $query->getScalarResult();
        $data    = ['previous' => 0, 'next' => 0];
        if (!empty($results)) {
            if (!empty($results[0]) && !empty($results[1])) {
                $previous         = $results[0];
                $next             = $results[1];
                $data['previous'] = (int)$previous['id'];
                $data['next']     = (int)$next['id'];
            } else if (!empty($results[0])) {
                $item   = $results[0];
                $termId = (int)$item['id'];
                if ($termId > $id) {
                    $data['next'] = $termId;
                } else {
                    $data['previous'] = $termId;
                }
            }
        }
        return $data;
    }

    /**
     * @param \Doctrine\ORM\QueryBuilder $query
     * @param $page
     * @param int $numberPerPage
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    protected function createPaginator(QueryBuilder $query, $page, $numberPerPage = 20)
    {
        $query->setFirstResult(($page - 1) * $numberPerPage)
            ->setMaxResults($numberPerPage);

        return new Paginator($query);
    }
}
