<?php

namespace App\Repository\Produit\Produit;

use App\Entity\Produit\Produit\Panier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

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

    public function findPanierOrganisationPagine($id, $page, $nombreParPage)
	{
		if($page < 1){
			throw new \InvalidArgumentException('Page inexistant');
		}
		$query = $this->createQueryBuilder('p')
                    ->leftJoin('p.organisation', 'o')
                    ->addSelect('o')
                    ->where('o.id = :id')
					->orderBy('p.date','DESC')
                    ->setParameter('id', $id)
					->getQuery();
		$query->setFirstResult(($page-1) * $nombreParPage)
			->setMaxResults($nombreParPage);
		return new Paginator($query);
	}

    public function findPanierAdminPagine($page, $nombreParPage)
	{
		if($page < 1){
			throw new \InvalidArgumentException('Page inexistant');
		}
		$query = $this->createQueryBuilder('p')
                    ->leftJoin('p.organisation', 'o')
                    ->addSelect('o')
					->orderBy('p.date','DESC')
					->getQuery();
		$query->setFirstResult(($page-1) * $nombreParPage)
			->setMaxResults($nombreParPage);
		return new Paginator($query);
	}

    public function findAmountCotationOrg($organisationId)
    {
        $query = $this->_em->createQuery('SELECT SUM(p.montant) FROM App\Entity\Produit\Produit\Panier p, App\Entity\Localisation\Organisation\Organisation o WHERE p.organisation  = o AND o.id = :idOrg');
        $query->setParameter('idOrg', $organisationId);
        return $query->getSingleScalarResult();
    }

    public function findCotationStatusOrg($organisationId, $status)
    {
        $query = $this->_em->createQuery('SELECT COUNT(p.id) FROM App\Entity\Produit\Produit\Panier p, App\Entity\Localisation\Organisation\Organisation o WHERE p.organisation  = o AND o.id = :idOrg AND p.status LIKE :status');
        $query->setParameter('idOrg', $organisationId);
        $query->setParameter('status', '%'.$status.'%');
        return (int) $query->getSingleScalarResult();
    }

    public function findAmountCotationStatusOrg($organisationId, $status)
    {
        $query = $this->_em->createQuery('SELECT SUM(p.montant) FROM App\Entity\Produit\Produit\Panier p, App\Entity\Localisation\Organisation\Organisation o WHERE p.organisation  = o AND o.id = :idOrg AND p.status LIKE :status');
        $query->setParameter('idOrg', $organisationId);
        $query->setParameter('status', '%'.$status.'%');
        return (int) $query->getSingleScalarResult();
    }


    public function findAmountCotationGlobal()
    {
        $query = $this->_em->createQuery('SELECT SUM(p.montant) FROM App\Entity\Produit\Produit\Panier p, App\Entity\Localisation\Organisation\Organisation o WHERE p.organisation  = o');
        return $query->getSingleScalarResult();
    }

    public function findCotationStatusGlobal($status)
    {
        $query = $this->_em->createQuery('SELECT COUNT(p.id) FROM App\Entity\Produit\Produit\Panier p, App\Entity\Localisation\Organisation\Organisation o WHERE p.organisation  = o AND p.status LIKE :status');
        $query->setParameter('status', '%'.$status.'%');
        return (int) $query->getSingleScalarResult();
    }

    public function findAmountCotationStatusGlobal($status)
    {
        $query = $this->_em->createQuery('SELECT SUM(p.montant) FROM App\Entity\Produit\Produit\Panier p, App\Entity\Localisation\Organisation\Organisation o WHERE p.organisation  = o AND p.status LIKE :status');
        $query->setParameter('status', '%'.$status.'%');
        return (int) $query->getSingleScalarResult();
    }
}
