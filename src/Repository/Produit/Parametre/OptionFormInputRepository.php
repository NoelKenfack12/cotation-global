<?php

namespace App\Repository\Produit\Parametre;

use App\Entity\Produit\Parametre\OptionFormInput;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OptionFormInput>
 *
 * @method OptionFormInput|null find($id, $lockMode = null, $lockVersion = null)
 * @method OptionFormInput|null findOneBy(array $criteria, array $orderBy = null)
 * @method OptionFormInput[]    findAll()
 * @method OptionFormInput[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OptionFormInputRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OptionFormInput::class);
    }

    public function add(OptionFormInput $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OptionFormInput $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return OptionFormInput[] Returns an array of OptionFormInput objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OptionFormInput
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
