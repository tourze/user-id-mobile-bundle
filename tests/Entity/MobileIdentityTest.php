<?php

namespace Tourze\UserIDMobileBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\UserIDBundle\Model\Identity;
use Tourze\UserIDMobileBundle\Entity\MobileIdentity;

class MobileIdentityTest extends TestCase
{
    public function testGetId_initialValueIsNull(): void
    {
        $entity = new MobileIdentity();
        $this->assertNull($entity->getId());
    }

    public function testGetSetMobileNumber(): void
    {
        $entity = new MobileIdentity();
        $mobileNumber = '13800138000';

        $this->assertSame($entity, $entity->setMobileNumber($mobileNumber));
        $this->assertSame($mobileNumber, $entity->getMobileNumber());
    }

    public function testGetSetMobileNumber_withEmptyString(): void
    {
        $entity = new MobileIdentity();
        $entity->setMobileNumber('');
        $this->assertSame('', $entity->getMobileNumber());
        $this->assertSame('', $entity->getIdentityValue());
    }

    public function testGetSetUser(): void
    {
        $entity = new MobileIdentity();
        $user = $this->createMock(UserInterface::class);

        $this->assertSame($entity, $entity->setUser($user));
        $this->assertSame($user, $entity->getUser());

        // 测试 null 值
        $this->assertSame($entity, $entity->setUser(null));
        $this->assertNull($entity->getUser());
    }

    public function testGetSetCreatedBy(): void
    {
        $entity = new MobileIdentity();
        $createdBy = 'user1';

        $this->assertSame($entity, $entity->setCreatedBy($createdBy));
        $this->assertSame($createdBy, $entity->getCreatedBy());

        // 测试 null 值
        $this->assertSame($entity, $entity->setCreatedBy(null));
        $this->assertNull($entity->getCreatedBy());
    }

    public function testGetSetUpdatedBy(): void
    {
        $entity = new MobileIdentity();
        $updatedBy = 'user1';

        $this->assertSame($entity, $entity->setUpdatedBy($updatedBy));
        $this->assertSame($updatedBy, $entity->getUpdatedBy());

        // 测试 null 值
        $this->assertSame($entity, $entity->setUpdatedBy(null));
        $this->assertNull($entity->getUpdatedBy());
    }

    public function testGetSetCreateTime(): void
    {
        $entity = new MobileIdentity();
        $date = new \DateTimeImmutable();

        $entity->setCreateTime($date);
        $this->assertSame($date, $entity->getCreateTime());

        // 测试 null 值
        $entity->setCreateTime(null);
        $this->assertNull($entity->getCreateTime());
    }

    public function testGetSetUpdateTime(): void
    {
        $entity = new MobileIdentity();
        $date = new \DateTimeImmutable();

        $entity->setUpdateTime($date);
        $this->assertSame($date, $entity->getUpdateTime());

        // 测试 null 值
        $entity->setUpdateTime(null);
        $this->assertNull($entity->getUpdateTime());
    }

    public function testGetIdentityValue(): void
    {
        $entity = new MobileIdentity();
        $mobileNumber = '13800138000';
        $entity->setMobileNumber($mobileNumber);

        $this->assertSame($mobileNumber, $entity->getIdentityValue());
    }

    public function testGetIdentityType(): void
    {
        $entity = new MobileIdentity();
        $this->assertSame(MobileIdentity::IDENTITY_TYPE, $entity->getIdentityType());
        $this->assertSame('mobile', $entity->getIdentityType());
    }


    public function testGetAccounts(): void
    {
        $entity = new MobileIdentity();
        // 已知返回数组，不需要断言
        $this->assertEmpty($entity->getAccounts());
    }
} 