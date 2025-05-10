<?php

namespace Tourze\UserIDMobileBundle\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tourze\UserIDMobileBundle\Repository\MobileIdentityRepository;
use Tourze\UserIDMobileBundle\Service\UserIdentityMobileService;

class UserIDMobileIntegrationTest extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return IntegrationTestKernel::class;
    }

    protected function setUp(): void
    {
        self::bootKernel();
    }

    public function testServiceRegistration(): void
    {
        $container = static::getContainer();
        $this->assertTrue($container->has(UserIdentityMobileService::class));
    }
    
    public function testRepositoryRegistration(): void
    {
        $container = static::getContainer();
        $this->assertTrue($container->has(MobileIdentityRepository::class));
    }
    
    public function testServicesConfiguration(): void
    {
        $container = static::getContainer();
        $this->assertTrue($container->has(UserIdentityMobileService::class));
        $this->assertTrue($container->has(MobileIdentityRepository::class));
    }
} 