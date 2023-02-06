<?php

namespace App\Repository\Localisation\Organisation;

use App\Entity\Localisation\Organisation\Serviceorganisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serviceorganisation>
 *
 * @method Serviceorganisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Serviceorganisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Serviceorganisation[]    findAll()
 * @method Serviceorganisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceorganisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serviceorganisation::class);
    }

    public function add(Serviceorganisation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Serviceorganisation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Serviceorganisation[] Returns an array of Serviceorganisation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Serviceorganisation
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
