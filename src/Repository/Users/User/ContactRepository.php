<?php

namespace App\Repository\Users\User;

use App\Entity\Users\User\Contact;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contact>
 *
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    public function add(Contact $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Contact $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findContactOrganisation($organisationId, $page, $nombreParPage)
    {
        if($page < 1){
			throw new \InvalidArgumentException('Page inexistant');
		}
		$query = $this->createQueryBuilder('c')
                    ->leftJoin('c.organisation', 'o')
                    ->addSelect('o')
                    ->where('o.id = :id')
					->orderBy('c.date','DESC')
                    ->setParameter('id', $organisationId)
					->getQuery();
		$query->setFirstResult(($page-1) * $nombreParPage)
			  ->setMaxResults($nombreParPage);
		return new Paginator($query);
    }

    public function findContactClient($page, $nombreParPage)
    {
        if($page < 1){
			throw new \InvalidArgumentException('Page inexistant');
		}
		$query = $this->createQueryBuilder('c')
                    ->leftJoin('c.organisation', 'o')
                    ->addSelect('o')
					->orderBy('c.date','DESC')
					->getQuery();
		$query->setFirstResult(($page-1) * $nombreParPage)
			  ->setMaxResults($nombreParPage);
		return new Paginator($query);
    }
}
