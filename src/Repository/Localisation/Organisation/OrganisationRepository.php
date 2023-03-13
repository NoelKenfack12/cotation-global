<?php

namespace App\Repository\Localisation\Organisation;

use App\Entity\Localisation\Organisation\Organisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Organisation>
 *
 * @method Organisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Organisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Organisation[]    findAll()
 * @method Organisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Organisation::class);
    }

    public function add(Organisation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Organisation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOrganisationPagine($page, $nombreParPage)
	{ 
		if($page < 1){
			throw new \InvalidArgumentException('Page inexistant');
		}
		$query = $this->createQueryBuilder('o')
					->orderBy('o.nom','ASC')
					->getQuery();
		$query->setFirstResult(($page-1) * $nombreParPage)
			->setMaxResults($nombreParPage);
		return new Paginator($query);
	}

    public function countOrganisationGlobal()
    {
        $query =  $this->_em->createQuery('SELECT COUNT(o.id) FROM App\Entity\Localisation\Organisation\Organisation o');
        return (int) $query->getSingleScalarResult();
    }
}
