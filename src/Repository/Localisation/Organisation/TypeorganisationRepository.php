<?php

namespace App\Repository\Localisation\Organisation;

use App\Entity\Localisation\Organisation\Typeorganisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Typeorganisation>
 *
 * @method Typeorganisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Typeorganisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Typeorganisation[]    findAll()
 * @method Typeorganisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeorganisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Typeorganisation::class);
    }

    public function add(Typeorganisation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Typeorganisation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Typeorganisation[] Returns an array of Typeorganisation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Typeorganisation
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
