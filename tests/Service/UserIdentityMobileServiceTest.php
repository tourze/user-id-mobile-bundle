<?php

namespace Tourze\UserIDMobileBundle\Tests\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\UserIDBundle\Contracts\IdentityInterface;
use Tourze\UserIDBundle\Service\UserIdentityService;
use Tourze\UserIDMobileBundle\Entity\MobileIdentity;
use Tourze\UserIDMobileBundle\Repository\MobileIdentityRepository;
use Tourze\UserIDMobileBundle\Service\UserIdentityMobileService;

class UserIdentityMobileServiceTest extends TestCase
{
    private MobileIdentityRepository $repository;
    private UserIdentityService $innerService;
    private UserIdentityMobileService $service;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(MobileIdentityRepository::class);
        $this->innerService = $this->createMock(UserIdentityService::class);
        $this->service = new UserIdentityMobileService($this->repository, $this->innerService);
    }

    public function testFindByType_withMobileType(): void
    {
        $mobileNumber = '13800138000';
        $identity = $this->createMock(MobileIdentity::class);

        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(['mobileNumber' => $mobileNumber])
            ->willReturn($identity);

        $this->innerService->expects($this->never())
            ->method('findByType');

        $result = $this->service->findByType(MobileIdentity::IDENTITY_TYPE, $mobileNumber);
        $this->assertSame($identity, $result);
    }

    public function testFindByType_withMobileTypeButNotFound(): void
    {
        $mobileNumber = '13800138000';

        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(['mobileNumber' => $mobileNumber])
            ->willReturn(null);

        $this->innerService->expects($this->once())
            ->method('findByType')
            ->with(MobileIdentity::IDENTITY_TYPE, $mobileNumber)
            ->willReturn(null);

        $result = $this->service->findByType(MobileIdentity::IDENTITY_TYPE, $mobileNumber);
        $this->assertNull($result);
    }

    public function testFindByType_withNonMobileType(): void
    {
        $type = 'email';
        $value = 'test@example.com';
        $identity = $this->createMock(IdentityInterface::class);

        $this->repository->expects($this->never())
            ->method('findOneBy');

        $this->innerService->expects($this->once())
            ->method('findByType')
            ->with($type, $value)
            ->willReturn($identity);

        $result = $this->service->findByType($type, $value);
        $this->assertSame($identity, $result);
    }

    public function testFindByType_withEmptyValue(): void
    {
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(['mobileNumber' => ''])
            ->willReturn(null);

        $this->innerService->expects($this->once())
            ->method('findByType')
            ->with(MobileIdentity::IDENTITY_TYPE, '')
            ->willReturn(null);

        $result = $this->service->findByType(MobileIdentity::IDENTITY_TYPE, '');
        $this->assertNull($result);
    }

    public function testFindByType_whenRepositoryThrowsException(): void
    {
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->willThrowException(new \RuntimeException('Database error'));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Database error');

        $this->service->findByType(MobileIdentity::IDENTITY_TYPE, '13800138000');
    }

    public function testFindByUser(): void
    {
        $user = $this->createMock(UserInterface::class);
        $mobileIdentity1 = $this->createMock(MobileIdentity::class);
        $mobileIdentity2 = $this->createMock(MobileIdentity::class);
        $otherIdentity = $this->createMock(IdentityInterface::class);

        $this->repository->expects($this->once())
            ->method('findBy')
            ->with(['user' => $user])
            ->willReturn([$mobileIdentity1, $mobileIdentity2]);

        $this->innerService->expects($this->once())
            ->method('findByUser')
            ->with($user)
            ->willReturn([$otherIdentity]);

        $result = $this->service->findByUser($user);

        $this->assertInstanceOf(\Traversable::class, $result);
        $items = iterator_to_array($result);

        $this->assertCount(3, $items);
        $this->assertSame($mobileIdentity1, $items[0]);
        $this->assertSame($mobileIdentity2, $items[1]);
        $this->assertSame($otherIdentity, $items[2]);
    }

    public function testFindByUser_withEmptyResults(): void
    {
        $user = $this->createMock(UserInterface::class);

        $this->repository->expects($this->once())
            ->method('findBy')
            ->with(['user' => $user])
            ->willReturn([]);

        $this->innerService->expects($this->once())
            ->method('findByUser')
            ->with($user)
            ->willReturn([]);

        $result = $this->service->findByUser($user);

        $this->assertInstanceOf(\Traversable::class, $result);
        $items = iterator_to_array($result);

        $this->assertCount(0, $items);
    }


    public function testFindByUser_whenInnerServiceReturnsEmptyArray(): void
    {
        $user = $this->createMock(UserInterface::class);
        $mobileIdentity = $this->createMock(MobileIdentity::class);

        $this->repository->expects($this->once())
            ->method('findBy')
            ->with(['user' => $user])
            ->willReturn([$mobileIdentity]);

        // 模拟内部服务返回空数组
        $this->innerService->expects($this->once())
            ->method('findByUser')
            ->with($user)
            ->willReturn([]);

        $result = $this->service->findByUser($user);
        $items = iterator_to_array($result);

        $this->assertCount(1, $items);
        $this->assertSame($mobileIdentity, $items[0]);
    }
}
