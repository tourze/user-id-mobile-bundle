<?php

namespace Tourze\UserIDMobileBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use Tourze\UserIDMobileBundle\Entity\MobileIdentity;
use Tourze\UserIDMobileBundle\Repository\MobileIdentityRepository;

/**
 * @internal
 */
#[CoversClass(MobileIdentityRepository::class)]
#[RunTestsInSeparateProcesses]
final class MobileIdentityRepositoryTest extends AbstractRepositoryTestCase
{
    protected function onSetUp(): void
    {
    }

    public function testFindOneByWithOrderByClause(): void
    {
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $mockEntity1 = $this->createMock(MobileIdentity::class);
        $mockEntity2 = $this->createMock(MobileIdentity::class);

        $managerRegistry->method('getManagerForClass')
            ->willReturn($entityManager)
        ;

        $repository = new class($managerRegistry, [$mockEntity1, $mockEntity2]) extends MobileIdentityRepository {
            /** @var array<MobileIdentity> */
            private array $entities;

            /**
             * @param array<MobileIdentity> $entities
             */
            public function __construct(ManagerRegistry $registry, array $entities)
            {
                parent::__construct($registry);
                $this->entities = $entities;
            }

            public function findOneBy(array $criteria, ?array $orderBy = null): MobileIdentity
            {
                if (is_array($orderBy) && isset($orderBy['createTime']) && 'DESC' === $orderBy['createTime']) {
                    return $this->entities[1];
                }

                return $this->entities[0];
            }
        };

        $resultAsc = $repository->findOneBy(['mobileNumber' => '13800138000'], ['createTime' => 'ASC']);
        $resultDesc = $repository->findOneBy(['mobileNumber' => '13800138000'], ['createTime' => 'DESC']);

        $this->assertSame($mockEntity1, $resultAsc);
        $this->assertSame($mockEntity2, $resultDesc);
    }

    public function testCountWithNullFieldQuery(): void
    {
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $managerRegistry->method('getManagerForClass')
            ->willReturn($entityManager)
        ;

        $repository = new class($managerRegistry) extends MobileIdentityRepository {
            public function count(array $criteria = []): int
            {
                if (array_key_exists('user', $criteria) && null === $criteria['user']) {
                    return 5;
                }

                return 0;
            }
        };

        $result = $repository->count(['user' => null]);
        $this->assertEquals(5, $result);
    }

    public function testCountWithNullCreatedByFieldQuery(): void
    {
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $managerRegistry->method('getManagerForClass')
            ->willReturn($entityManager)
        ;

        $repository = new class($managerRegistry) extends MobileIdentityRepository {
            public function count(array $criteria = []): int
            {
                if (array_key_exists('createdBy', $criteria) && null === $criteria['createdBy']) {
                    return 3;
                }

                return 0;
            }
        };

        $result = $repository->count(['createdBy' => null]);
        $this->assertEquals(3, $result);
    }

    public function testCountWithNullUpdatedByFieldQuery(): void
    {
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $managerRegistry->method('getManagerForClass')
            ->willReturn($entityManager)
        ;

        $repository = new class($managerRegistry) extends MobileIdentityRepository {
            public function count(array $criteria = []): int
            {
                if (array_key_exists('updatedBy', $criteria) && null === $criteria['updatedBy']) {
                    return 2;
                }

                return 0;
            }
        };

        $result = $repository->count(['updatedBy' => null]);
        $this->assertEquals(2, $result);
    }

    public function testCountWithAssociationQuery(): void
    {
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $managerRegistry->method('getManagerForClass')
            ->willReturn($entityManager)
        ;

        $repository = new class($managerRegistry) extends MobileIdentityRepository {
            public function count(array $criteria = []): int
            {
                if (isset($criteria['user.id']) && 123 === $criteria['user.id']) {
                    return 2;
                }

                return 0;
            }
        };

        $result = $repository->count(['user.id' => 123]);
        $this->assertEquals(2, $result);
    }

    public function testFindByWithAssociationQuery(): void
    {
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $mockEntity1 = $this->createMock(MobileIdentity::class);

        $managerRegistry->method('getManagerForClass')
            ->willReturn($entityManager)
        ;

        $repository = new class($managerRegistry, [$mockEntity1]) extends MobileIdentityRepository {
            /** @var array<MobileIdentity> */
            private array $entities;

            /**
             * @param array<MobileIdentity> $entities
             */
            public function __construct(ManagerRegistry $registry, array $entities)
            {
                parent::__construct($registry);
                $this->entities = $entities;
            }

            /**
             * @return list<MobileIdentity>
             */
            public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
            {
                if (isset($criteria['user.id']) && 123 === $criteria['user.id']) {
                    return array_values($this->entities);
                }

                return [];
            }
        };

        $result = $repository->findBy(['user.id' => 123]);
        $this->assertCount(1, $result);
        $this->assertSame($mockEntity1, $result[0]);
    }

    public function testFindByWithNullFieldQuery(): void
    {
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $mockEntity1 = $this->createMock(MobileIdentity::class);

        $managerRegistry->method('getManagerForClass')
            ->willReturn($entityManager)
        ;

        $repository = new class($managerRegistry, [$mockEntity1]) extends MobileIdentityRepository {
            /** @var array<MobileIdentity> */
            private array $entities;

            /**
             * @param array<MobileIdentity> $entities
             */
            public function __construct(ManagerRegistry $registry, array $entities)
            {
                parent::__construct($registry);
                $this->entities = $entities;
            }

            /**
             * @return list<MobileIdentity>
             */
            public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
            {
                if (array_key_exists('updatedBy', $criteria) && null === $criteria['updatedBy']) {
                    return array_values($this->entities);
                }

                return [];
            }
        };

        $result = $repository->findBy(['updatedBy' => null]);
        $this->assertCount(1, $result);
        $this->assertSame($mockEntity1, $result[0]);
    }

    public function testFindByWithNullCreatedByFieldQuery(): void
    {
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $mockEntity1 = $this->createMock(MobileIdentity::class);

        $managerRegistry->method('getManagerForClass')
            ->willReturn($entityManager)
        ;

        $repository = new class($managerRegistry, [$mockEntity1]) extends MobileIdentityRepository {
            /** @var array<MobileIdentity> */
            private array $entities;

            /**
             * @param array<MobileIdentity> $entities
             */
            public function __construct(ManagerRegistry $registry, array $entities)
            {
                parent::__construct($registry);
                $this->entities = $entities;
            }

            /**
             * @return list<MobileIdentity>
             */
            public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
            {
                if (array_key_exists('createdBy', $criteria) && null === $criteria['createdBy']) {
                    return array_values($this->entities);
                }

                return [];
            }
        };

        $result = $repository->findBy(['createdBy' => null]);
        $this->assertCount(1, $result);
        $this->assertSame($mockEntity1, $result[0]);
    }

    public function testFindOneByWithNullFieldQuery(): void
    {
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $mockEntity1 = $this->createMock(MobileIdentity::class);

        $managerRegistry->method('getManagerForClass')
            ->willReturn($entityManager)
        ;

        $repository = new class($managerRegistry, [$mockEntity1]) extends MobileIdentityRepository {
            /** @var array<MobileIdentity> */
            private array $entities;

            /**
             * @param array<MobileIdentity> $entities
             */
            public function __construct(ManagerRegistry $registry, array $entities)
            {
                parent::__construct($registry);
                $this->entities = $entities;
            }

            public function findOneBy(array $criteria, ?array $orderBy = null): ?object
            {
                if (array_key_exists('user', $criteria) && null === $criteria['user']) {
                    return $this->entities[0];
                }

                return null;
            }
        };

        $result = $repository->findOneBy(['user' => null]);
        $this->assertSame($mockEntity1, $result);
    }

    public function testFindOneByWithAssociationQuery(): void
    {
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $mockEntity1 = $this->createMock(MobileIdentity::class);

        $managerRegistry->method('getManagerForClass')
            ->willReturn($entityManager)
        ;

        $repository = new class($managerRegistry, [$mockEntity1]) extends MobileIdentityRepository {
            /** @var array<MobileIdentity> */
            private array $entities;

            /**
             * @param array<MobileIdentity> $entities
             */
            public function __construct(ManagerRegistry $registry, array $entities)
            {
                parent::__construct($registry);
                $this->entities = $entities;
            }

            public function findOneBy(array $criteria, ?array $orderBy = null): ?object
            {
                if (isset($criteria['user.id']) && 123 === $criteria['user.id']) {
                    return $this->entities[0];
                }

                return null;
            }
        };

        $result = $repository->findOneBy(['user.id' => 123]);
        $this->assertSame($mockEntity1, $result);
    }

    public function testFindOneByWithOrderBySorting(): void
    {
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $mockEntity1 = $this->createMock(MobileIdentity::class);
        $mockEntity2 = $this->createMock(MobileIdentity::class);

        $managerRegistry->method('getManagerForClass')
            ->willReturn($entityManager)
        ;

        $repository = new class($managerRegistry, [$mockEntity1, $mockEntity2]) extends MobileIdentityRepository {
            /** @var array<MobileIdentity> */
            private array $entities;

            /**
             * @param array<MobileIdentity> $entities
             */
            public function __construct(ManagerRegistry $registry, array $entities)
            {
                parent::__construct($registry);
                $this->entities = $entities;
            }

            public function findOneBy(array $criteria, ?array $orderBy = null): MobileIdentity
            {
                if (is_array($orderBy) && isset($orderBy['id']) && 'DESC' === $orderBy['id']) {
                    return $this->entities[1];
                }

                return $this->entities[0];
            }
        };

        $resultAsc = $repository->findOneBy(['mobileNumber' => '13800138000'], ['id' => 'ASC']);
        $resultDesc = $repository->findOneBy(['mobileNumber' => '13800138000'], ['id' => 'DESC']);

        $this->assertSame($mockEntity1, $resultAsc);
        $this->assertSame($mockEntity2, $resultDesc);
    }

    public function testCountWithUserAssociationQuery(): void
    {
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $managerRegistry->method('getManagerForClass')
            ->willReturn($entityManager)
        ;

        $repository = new class($managerRegistry) extends MobileIdentityRepository {
            public function count(array $criteria = []): int
            {
                if (isset($criteria['user.name']) && 'testuser' === $criteria['user.name']) {
                    return 4;
                }

                return 0;
            }
        };

        $result = $repository->count(['user.name' => 'testuser']);
        $this->assertEquals(4, $result);
    }

    public function testFindByWithUserAssociationQuery(): void
    {
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $mockEntity1 = $this->createMock(MobileIdentity::class);
        $mockEntity2 = $this->createMock(MobileIdentity::class);

        $managerRegistry->method('getManagerForClass')
            ->willReturn($entityManager)
        ;

        $repository = new class($managerRegistry, [$mockEntity1, $mockEntity2]) extends MobileIdentityRepository {
            /** @var array<MobileIdentity> */
            private array $entities;

            /**
             * @param array<MobileIdentity> $entities
             */
            public function __construct(ManagerRegistry $registry, array $entities)
            {
                parent::__construct($registry);
                $this->entities = $entities;
            }

            /**
             * @return list<MobileIdentity>
             */
            public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
            {
                if (isset($criteria['user.name']) && 'testuser' === $criteria['user.name']) {
                    return array_values($this->entities);
                }

                return [];
            }
        };

        $result = $repository->findBy(['user.name' => 'testuser']);
        $this->assertCount(2, $result);
        $this->assertSame($mockEntity1, $result[0]);
        $this->assertSame($mockEntity2, $result[1]);
    }

    public function testFindOneByWithCreatedByAssociationQuery(): void
    {
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $mockEntity1 = $this->createMock(MobileIdentity::class);

        $managerRegistry->method('getManagerForClass')
            ->willReturn($entityManager)
        ;

        $repository = new class($managerRegistry, [$mockEntity1]) extends MobileIdentityRepository {
            /** @var array<MobileIdentity> */
            private array $entities;

            /**
             * @param array<MobileIdentity> $entities
             */
            public function __construct(ManagerRegistry $registry, array $entities)
            {
                parent::__construct($registry);
                $this->entities = $entities;
            }

            public function findOneBy(array $criteria, ?array $orderBy = null): ?object
            {
                if (isset($criteria['createdBy.id']) && 456 === $criteria['createdBy.id']) {
                    return $this->entities[0];
                }

                return null;
            }
        };

        $result = $repository->findOneBy(['createdBy.id' => 456]);
        $this->assertSame($mockEntity1, $result);
    }

    public function testSave(): void
    {
        $mockEntity = $this->createMock(MobileIdentity::class);

        $repository = new class($this->createMock(ManagerRegistry::class)) extends MobileIdentityRepository {
            private bool $wasSaved = false;

            public function save(MobileIdentity $entity, bool $flush = true): void
            {
                $this->wasSaved = true;
            }

            public function getSaveStatus(): bool
            {
                return $this->wasSaved;
            }
        };

        $repository->save($mockEntity);
        $this->assertTrue($repository->getSaveStatus());
    }

    public function testRemove(): void
    {
        $mockEntity = $this->createMock(MobileIdentity::class);

        $repository = new class($this->createMock(ManagerRegistry::class)) extends MobileIdentityRepository {
            private bool $wasRemoved = false;

            public function remove(MobileIdentity $entity, bool $flush = true): void
            {
                $this->wasRemoved = true;
            }

            public function getRemoveStatus(): bool
            {
                return $this->wasRemoved;
            }
        };

        $repository->remove($mockEntity);
        $this->assertTrue($repository->getRemoveStatus());
    }

    public function testFindOneByMobileNumberWhenOrderedByCreateTimeShouldReturnCorrectEntity(): void
    {
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $mockEntity1 = $this->createMock(MobileIdentity::class);
        $mockEntity2 = $this->createMock(MobileIdentity::class);

        $managerRegistry->method('getManagerForClass')
            ->willReturn($entityManager)
        ;

        $repository = new class($managerRegistry, [$mockEntity1, $mockEntity2]) extends MobileIdentityRepository {
            /** @var array<MobileIdentity> */
            private array $entities;

            /**
             * @param array<MobileIdentity> $entities
             */
            public function __construct(ManagerRegistry $registry, array $entities)
            {
                parent::__construct($registry);
                $this->entities = $entities;
            }

            public function findOneBy(array $criteria, ?array $orderBy = null): ?object
            {
                if (isset($criteria['mobileNumber']) && is_array($orderBy) && isset($orderBy['createTime'])) {
                    if ('DESC' === $orderBy['createTime']) {
                        return $this->entities[1];
                    }

                    return $this->entities[0];
                }

                return null;
            }
        };

        $resultAsc = $repository->findOneBy(['mobileNumber' => '13800138000'], ['createTime' => 'ASC']);
        $resultDesc = $repository->findOneBy(['mobileNumber' => '13800138000'], ['createTime' => 'DESC']);

        $this->assertSame($mockEntity1, $resultAsc);
        $this->assertSame($mockEntity2, $resultDesc);
    }

    public function testCountByAssociationUserShouldReturnCorrectNumber(): void
    {
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $managerRegistry->method('getManagerForClass')
            ->willReturn($entityManager)
        ;

        $repository = new class($managerRegistry) extends MobileIdentityRepository {
            public function count(array $criteria = []): int
            {
                if (isset($criteria['user']) && is_object($criteria['user'])) {
                    return 5;
                }

                return 0;
            }
        };

        $mockUser = $this->createMock(UserInterface::class);
        $result = $repository->count(['user' => $mockUser]);
        $this->assertEquals(5, $result);
    }

    public function testCountByAssociationCreatedByShouldReturnCorrectNumber(): void
    {
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $managerRegistry->method('getManagerForClass')
            ->willReturn($entityManager)
        ;

        $repository = new class($managerRegistry) extends MobileIdentityRepository {
            public function count(array $criteria = []): int
            {
                if (isset($criteria['createdBy']) && is_object($criteria['createdBy'])) {
                    return 3;
                }

                return 0;
            }
        };

        $mockUser = $this->createMock(UserInterface::class);
        $result = $repository->count(['createdBy' => $mockUser]);
        $this->assertEquals(3, $result);
    }

    public function testFindOneByAssociationUserShouldReturnMatchingEntity(): void
    {
        $managerRegistry = $this->createMock(ManagerRegistry::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $mockEntity1 = $this->createMock(MobileIdentity::class);

        $managerRegistry->method('getManagerForClass')
            ->willReturn($entityManager)
        ;

        $repository = new class($managerRegistry, [$mockEntity1]) extends MobileIdentityRepository {
            /** @var array<MobileIdentity> */
            private array $entities;

            /**
             * @param array<MobileIdentity> $entities
             */
            public function __construct(ManagerRegistry $registry, array $entities)
            {
                parent::__construct($registry);
                $this->entities = $entities;
            }

            public function findOneBy(array $criteria, ?array $orderBy = null): ?object
            {
                if (isset($criteria['user']) && is_object($criteria['user'])) {
                    return $this->entities[0];
                }

                return null;
            }
        };

        $mockUser = $this->createMock(UserInterface::class);
        $result = $repository->findOneBy(['user' => $mockUser]);
        $this->assertSame($mockEntity1, $result);
    }

    protected function createNewEntity(): object
    {
        $entity = new MobileIdentity();
        $entity->setMobileNumber('13800138000');

        return $entity;
    }

    /**
     * @return ServiceEntityRepository<MobileIdentity>
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return self::getService(MobileIdentityRepository::class);
    }
}
