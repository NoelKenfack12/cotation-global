<?php

namespace App\Repository\Produit\Produit;

use App\Entity\Produit\Produit\Typeproduit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Typeproduit>
 *
 * @method Typeproduit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Typeproduit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Typeproduit[]    findAll()
 * @method Typeproduit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeproduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Typeproduit::class);
    }

    public function add(Typeproduit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Typeproduit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function myfindAll()
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.rang', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
