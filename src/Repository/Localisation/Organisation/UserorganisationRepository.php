<?php

namespace App\Repository\Localisation\Organisation;

use App\Entity\Localisation\Organisation\Userorganisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Userorganisation>
 *
 * @method Userorganisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Userorganisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Userorganisation[]    findAll()
 * @method Userorganisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserorganisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Userorganisation::class);
    }

    public function add(Userorganisation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Userorganisation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findUserOrganisation($organisationId)
    {
        $query =  $this->_em->createQuery('SELECT COUNT(uo) FROM App\Entity\Localisation\Organisation\Userorganisation uo, App\Entity\Localisation\Organisation\Organisation o WHERE uo.organisation = o AND o.id = :organisationId');
        $query->setParameter('organisationId',$organisationId);
        return (int) $query->getSingleScalarResult();
    }

    public function countAdminGlobal()
    {
        $query =  $this->_em->createQuery('SELECT COUNT(uo) FROM App\Entity\Localisation\Organisation\Userorganisation uo, App\Entity\Localisation\Organisation\Organisation o WHERE uo.organisation = o');
        return (int) $query->getSingleScalarResult();
    }
}
