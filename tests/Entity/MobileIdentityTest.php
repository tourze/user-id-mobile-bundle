<?php

namespace Tourze\UserIDMobileBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use Tourze\UserIDBundle\Model\Identity;
use Tourze\UserIDMobileBundle\Entity\MobileIdentity;

/**
 * @internal
 */
#[CoversClass(MobileIdentity::class)]
final class MobileIdentityTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new MobileIdentity();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        yield 'mobileNumber' => ['mobileNumber', 'test_value'];
    }

    public function testGetIdInitialValueIsNull(): void
    {
        $entity = new MobileIdentity();
        $this->assertNull($entity->getId());
    }

    public function testGetSetMobileNumber(): void
    {
        $entity = new MobileIdentity();
        $mobileNumber = '13800138000';

        $entity->setMobileNumber($mobileNumber);
        $this->assertSame($mobileNumber, $entity->getMobileNumber());
    }

    public function testGetSetMobileNumberWithEmptyString(): void
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

        $entity->setUser($user);
        $this->assertSame($user, $entity->getUser());

        // 测试 null 值
        $entity->setUser(null);
        $this->assertNull($entity->getUser());
    }

    public function testGetSetCreatedBy(): void
    {
        $entity = new MobileIdentity();
        $createdBy = 'user1';

        $entity->setCreatedBy($createdBy);
        $this->assertSame($createdBy, $entity->getCreatedBy());

        // 测试 null 值
        $entity->setCreatedBy(null);
        $this->assertNull($entity->getCreatedBy());
    }

    public function testGetSetUpdatedBy(): void
    {
        $entity = new MobileIdentity();
        $updatedBy = 'user1';

        $entity->setUpdatedBy($updatedBy);
        $this->assertSame($updatedBy, $entity->getUpdatedBy());

        // 测试 null 值
        $entity->setUpdatedBy(null);
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
        $accounts = $entity->getAccounts();
        $this->assertIsArray($accounts);
        $this->assertEmpty($accounts);
    }

    public function testGetIdentityArray(): void
    {
        $entity = new MobileIdentity();
        $entity->setMobileNumber('13800138000');
        // 手动设置ID，因为这是一个单元测试
        $reflectionClass = new \ReflectionClass($entity);
        $idProperty = $reflectionClass->getProperty('id');
        $idProperty->setValue($entity, '123456789');

        $identities = iterator_to_array($entity->getIdentityArray());
        $this->assertCount(1, $identities);

        $identity = $identities[0];
        $this->assertEquals('123456789', $identity->getId());
        $this->assertEquals('mobile', $identity->getIdentityType());
        $this->assertEquals('13800138000', $identity->getIdentityValue());
    }

    public function testToString(): void
    {
        $entity = new MobileIdentity();

        // 测试空的手机号码
        $this->assertSame('', (string) $entity);

        // 测试有手机号码
        $entity->setMobileNumber('13800138000');
        $this->assertSame('13800138000', (string) $entity);
    }
}
