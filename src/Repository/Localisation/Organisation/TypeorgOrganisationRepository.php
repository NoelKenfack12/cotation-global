<?php

namespace App\Repository\Localisation\Organisation;

use App\Entity\Localisation\Organisation\TypeorgOrganisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeorgOrganisation>
 *
 * @method TypeorgOrganisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeorgOrganisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeorgOrganisation[]    findAll()
 * @method TypeorgOrganisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeorgOrganisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeorgOrganisation::class);
    }

    public function add(TypeorgOrganisation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TypeorgOrganisation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllOrganisationType($OrgId)
    {
        $query =  $this->_em->createQuery('SELECT t.id as id FROM App\Entity\Localisation\Organisation\TypeorgOrganisation to, App\Entity\Localisation\Organisation\Typeorganisation t, App\Entity\Localisation\Organisation\Organisation o WHERE to.typeorganisation = t AND to.organisation = o AND o.id = :id');
        $query->setParameter('id',$OrgId);
        return array_column($query->getArrayResult(), 'id');
    }
}
