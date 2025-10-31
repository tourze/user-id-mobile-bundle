<?php

namespace Tourze\UserIDMobileBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use Tourze\UserIDMobileBundle\Entity\MobileIdentity;
use Tourze\UserIDMobileBundle\Service\UserIdentityMobileService;

/**
 * @internal
 */
#[CoversClass(UserIdentityMobileService::class)]
#[RunTestsInSeparateProcesses]
final class UserIdentityMobileServiceTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
    }

    public function testFindByTypeWithNonMobileType(): void
    {
        $service = self::getService(UserIdentityMobileService::class);
        $result = $service->findByType('other', 'test@example.com');

        $this->assertNull($result);
    }

    public function testFindByTypeWithMobileTypeNotFound(): void
    {
        $service = self::getService(UserIdentityMobileService::class);
        $result = $service->findByType(MobileIdentity::IDENTITY_TYPE, '13800138000');

        $this->assertNull($result);
    }

    public function testFindByTypeWithMobileTypeFound(): void
    {
        $user = $this->createNormalUser('test@example.com', 'password123');

        $mobileIdentity = new MobileIdentity();
        $mobileIdentity->setMobileNumber('13800138000');
        $mobileIdentity->setUser($user);
        $mobileIdentity->setCreatedBy('system');
        $mobileIdentity->setCreateTime(new \DateTimeImmutable());

        self::getEntityManager()->persist($mobileIdentity);
        self::getEntityManager()->flush();

        $service = self::getService(UserIdentityMobileService::class);
        $result = $service->findByType(MobileIdentity::IDENTITY_TYPE, '13800138000');

        $this->assertNotNull($result);
        $this->assertInstanceOf(MobileIdentity::class, $result);
        $this->assertSame('13800138000', $result->getMobileNumber());
    }

    public function testFindByUser(): void
    {
        $user = $this->createNormalUser('test@example.com', 'password123');

        $mobileIdentity = new MobileIdentity();
        $mobileIdentity->setMobileNumber('13800138000');
        $mobileIdentity->setUser($user);
        $mobileIdentity->setCreatedBy('system');
        $mobileIdentity->setCreateTime(new \DateTimeImmutable());

        self::getEntityManager()->persist($mobileIdentity);
        self::getEntityManager()->flush();

        $service = self::getService(UserIdentityMobileService::class);
        $results = iterator_to_array($service->findByUser($user));

        $this->assertCount(1, $results);
        $this->assertInstanceOf(MobileIdentity::class, $results[0]);
        $this->assertSame('13800138000', $results[0]->getMobileNumber());
    }
}
