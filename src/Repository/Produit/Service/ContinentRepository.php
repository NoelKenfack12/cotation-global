<?php

namespace App\Repository\Produit\Service;

use Doctrine\ORM\EntityRepository;
use App\Entity\Produit\Service\Continent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * ContinentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContinentRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Continent::class);
    }

    public function add(Continent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

	public function myFindBy()
	{
		$query = $this->createQueryBuilder('c')
					->orderBy('c.nom','ASC')
					->getQuery();
		return $query->getResult();
	}
}