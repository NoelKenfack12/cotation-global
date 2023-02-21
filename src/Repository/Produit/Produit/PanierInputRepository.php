<?php

namespace App\Repository\Produit\Produit;

use App\Entity\Produit\Produit\PanierInput;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PanierInput>
 *
 * @method PanierInput|null find($id, $lockMode = null, $lockVersion = null)
 * @method PanierInput|null findOneBy(array $criteria, array $orderBy = null)
 * @method PanierInput[]    findAll()
 * @method PanierInput[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PanierInputRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PanierInput::class);
    }

    public function add(PanierInput $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PanierInput $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findInputPanier($panierId, $position)
    {
        $query = $this->createQueryBuilder('pi')
                    ->leftJoin('pi.forminput', 'f')
                    ->leftJoin('pi.panier', 'p')
                    ->addSelect('p')
                    ->addSelect('f')
                    ->where('p.id = :id AND f.position LIKE :position')
					->orderBy('pi.date','DESC')
                    ->setParameter('id', $panierId)
                    ->setParameter('position', '%'.$position.'%')
					->getQuery();

        return $query->getResult();
    }
}
