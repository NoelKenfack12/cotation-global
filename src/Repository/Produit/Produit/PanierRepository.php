<?php

namespace App\Repository\Produit\Produit;

use App\Entity\Produit\Produit\Panier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Panier>
 *
 * @method Panier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Panier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Panier[]    findAll()
 * @method Panier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PanierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Panier::class);
    }

    public function add(Panier $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Panier $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function countPanierOrganisation($organisationId)
    {
        $query = $this->_em->createQuery('SELECT COUNT(p.id) FROM App\Entity\Produit\Produit\Panier p, App\Entity\Localisation\Organisation\Organisation o WHERE p.organisation = o AND o.id = :id');
        $query->setParameter('id', $organisationId);
        
        return (int) $query->getSingleScalarResult();
    }
}
