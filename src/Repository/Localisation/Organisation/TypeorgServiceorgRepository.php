<?php

namespace App\Repository\Localisation\Organisation;

use App\Entity\Localisation\Organisation\TypeorgServiceorg;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeorgServiceorg>
 *
 * @method TypeorgServiceorg|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeorgServiceorg|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeorgServiceorg[]    findAll()
 * @method TypeorgServiceorg[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeorgServiceorgRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeorgServiceorg::class);
    }

    public function add(TypeorgServiceorg $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TypeorgServiceorg $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllServiceType($typeOrgId)
    {
        $query =  $this->_em->createQuery('SELECT s.id as id FROM App\Entity\Localisation\Organisation\TypeorgServiceorg ts, App\Entity\Localisation\Organisation\Typeorganisation t, App\Entity\Localisation\Organisation\Serviceorganisation s WHERE ts.typeorganisation = t AND ts.serviceorganisation = s AND t.id = :id');
        $query->setParameter('id',$typeOrgId);
        return array_column($query->getArrayResult(), 'id');
    }
}
