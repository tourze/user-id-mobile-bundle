<?php

namespace Tourze\UserIDMobileBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use Tourze\UserIDMobileBundle\Entity\MobileIdentity;

/**
 * @internal
 */
#[CoversClass(MobileIdentity::class)]
final class EntityMappingTest extends AbstractEntityTestCase
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

    public function testEntityConstant(): void
    {
        $this->assertEquals('mobile', MobileIdentity::IDENTITY_TYPE);
    }

    public function testEntityIsInstantiable(): void
    {
        $entity = new MobileIdentity();
        $this->assertInstanceOf(MobileIdentity::class, $entity);
    }
}
