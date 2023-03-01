<?php

namespace App\Repository\Produit\Produit;

use App\Entity\Produit\Produit\ProduitOrganisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<ProduitOrganisation>
 *
 * @method ProduitOrganisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProduitOrganisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProduitOrganisation[]    findAll()
 * @method ProduitOrganisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitOrganisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProduitOrganisation::class);
    }

    public function add(ProduitOrganisation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProduitOrganisation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findProduitOrganisationPagine($id, $page, $nombreParPage)
	{
		if($page < 1){
			throw new \InvalidArgumentException('Page inexistant');
		}
		$query = $this->createQueryBuilder('p')
                    ->leftJoin('p.organisation', 'o')
                    ->leftJoin('p.produit', 'pt')
                    ->addSelect('o')
                    ->where('o.id = :id')
					->orderBy('pt.nom','ASC')
                    ->setParameter('id', $id)
					->getQuery();
		$query->setFirstResult(($page-1) * $nombreParPage)
			->setMaxResults($nombreParPage);
		return new Paginator($query);
	}

    public function findProduitOrganisationArray($organisationId)
    {
        $query =  $this->_em->createQuery('SELECT p.id as id FROM App\Entity\Produit\Produit\ProduitOrganisation po, App\Entity\Localisation\Organisation\Organisation o, App\Entity\Produit\Produit\Produit p WHERE po.organisation = o AND po.produit = p AND o.id = :organisationId');
        $query->setParameter('organisationId',$organisationId);
        return array_column($query->getArrayResult(), 'id');
    }
}
