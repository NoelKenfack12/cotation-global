<?php

namespace App\Repository\Produit\Produit;

use App\Entity\Produit\Produit\Forfaitpanier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Forfaitpanier>
 *
 * @method Forfaitpanier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Forfaitpanier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Forfaitpanier[]    findAll()
 * @method Forfaitpanier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForfaitpanierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Forfaitpanier::class);
    }

    public function add(Forfaitpanier $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Forfaitpanier $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Forfaitpanier[] Returns an array of Forfaitpanier objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Forfaitpanier
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
