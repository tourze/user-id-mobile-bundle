<?php

namespace Tourze\UserIDMobileBundle\Tests\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Tourze\UserIDMobileBundle\Entity\MobileIdentity;
use Tourze\UserIDMobileBundle\Repository\MobileIdentityRepository;

class MobileIdentityRepositoryTest extends TestCase
{
    private ManagerRegistry $registry;
    private EntityManagerInterface $entityManager;
    private MobileIdentityRepository $repository;
    
    protected function setUp(): void
    {
        $this->registry = $this->createMock(ManagerRegistry::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        
        $this->registry->expects($this->any())
            ->method('getManagerForClass')
            ->with(MobileIdentity::class)
            ->willReturn($this->entityManager);
            
        $this->repository = new MobileIdentityRepository($this->registry);
    }
    
    public function testConstructor(): void
    {
        $this->assertInstanceOf(MobileIdentityRepository::class, $this->repository);
    }
} 