<?php

namespace App\Repository\Produit\Produit;

use App\Entity\Produit\Produit\Produitpanier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produitpanier>
 *
 * @method Produitpanier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produitpanier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produitpanier[]    findAll()
 * @method Produitpanier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitpanierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produitpanier::class);
    }

    public function add(Produitpanier $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Produitpanier $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findProduitPanierType($panierId, $typeproduitId)
    {
        $query = $this->createQueryBuilder('pp')
                    ->leftJoin('pp.panier', 'p')
                    ->leftJoin('pp.produitOrganisation', 'po')
                    ->leftJoin('po.produit', 'pd')
                    ->leftJoin('pd.typeproduit', 'tp')
                    ->addSelect('p')
                    ->addSelect('po')
                    ->addSelect('pd')
                    ->addSelect('tp')
                    ->where('p.id = :panierId AND tp.id = :typeproduitId')
                    ->setParameter('panierId', $panierId)
                    ->setParameter('typeproduitId', $typeproduitId)
					->getQuery();
        return $query->getResult();
    }
}
