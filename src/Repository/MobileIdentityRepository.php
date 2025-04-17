<?php

namespace Tourze\UserIDMobileBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\UserIDMobileBundle\Entity\MobileIdentity;

/**
 * @method MobileIdentity|null find($id, $lockMode = null, $lockVersion = null)
 * @method MobileIdentity|null findOneBy(array $criteria, array $orderBy = null)
 * @method MobileIdentity[]    findAll()
 * @method MobileIdentity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MobileIdentityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MobileIdentity::class);
    }
}
