<?php

namespace App\Repository;

use App\Entity\CarbonOffset;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CarbonOffset|null find($id, $lockMode = null, $lockVersion = null)
 * @method CarbonOffset|null findOneBy(array $criteria, array $orderBy = null)
 * @method CarbonOffset[]    findAll()
 * @method CarbonOffset[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarbonOffsetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarbonOffset::class);
    }

    // /**
    //  * @return CarbonOffset[] Returns an array of CarbonOffset objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CarbonOffset
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
