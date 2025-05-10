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
        $date = new \DateTime();
        
        $entity->setCreateTime($date);
        $this->assertSame($date, $entity->getCreateTime());
        
        // 测试 null 值
        $entity->setCreateTime(null);
        $this->assertNull($entity->getCreateTime());
    }
    
    public function testGetSetUpdateTime(): void
    {
        $entity = new MobileIdentity();
        $date = new \DateTime();
        
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
    
    /**
     * @requires PHP < 8.4
     */
    public function testGetIdentityArray(): void
    {
        $entity = new MobileIdentity();
        // 使用反射设置 id 值，因为它是通过 ORM 生成的
        $reflection = new \ReflectionClass($entity);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($entity, '123456789');
        
        $entity->setMobileNumber('13800138000');
        $date = new \DateTime('2023-01-01 12:00:00');
        $entity->setCreateTime($date);
        $entity->setUpdateTime($date);
        
        $iterator = $entity->getIdentityArray();
        $this->assertInstanceOf(\Traversable::class, $iterator);
        
        $items = iterator_to_array($iterator);
        $this->assertCount(1, $items);
        $this->assertInstanceOf(Identity::class, $items[0]);
        $this->assertSame('mobile', $items[0]->getType());
        $this->assertSame('13800138000', $items[0]->getValue());
        
        $extra = $items[0]->getExtra();
        $this->assertIsArray($extra);
        $this->assertArrayHasKey('createTime', $extra);
        $this->assertArrayHasKey('updateTime', $extra);
        $this->assertSame('2023-01-01 12:00:00', $extra['createTime']);
        $this->assertSame('2023-01-01 12:00:00', $extra['updateTime']);
    }
    
    /**
     * @requires PHP < 8.4
     */
    public function testGetIdentityArray_withNullDates(): void
    {
        $entity = new MobileIdentity();
        // 使用反射设置 id 值，因为它是通过 ORM 生成的
        $reflection = new \ReflectionClass($entity);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($entity, '123456789');
        
        $entity->setMobileNumber('13800138000');
        
        $iterator = $entity->getIdentityArray();
        $items = iterator_to_array($iterator);
        
        $extra = $items[0]->getExtra();
        $this->assertNull($extra['createTime']);
        $this->assertNull($extra['updateTime']);
    }
    
    /**
     * @requires PHP < 8.4
     */
    public function testGetIdentityArray_withId(): void
    {
        $entity = new MobileIdentity();
        // 使用反射设置 id 值，因为它是通过 ORM 生成的
        $reflection = new \ReflectionClass($entity);
        $idProperty = $reflection->getProperty('id');
        $idProperty->setAccessible(true);
        $idProperty->setValue($entity, '123456789');
        
        $entity->setMobileNumber('13800138000');
        
        $iterator = $entity->getIdentityArray();
        $items = iterator_to_array($iterator);
        
        $this->assertCount(1, $items);
        $this->assertEquals('123456789', $items[0]->getId());
    }
    
    public function testGetAccounts(): void
    {
        $entity = new MobileIdentity();
        $this->assertIsArray($entity->getAccounts());
        $this->assertEmpty($entity->getAccounts());
    }
} 