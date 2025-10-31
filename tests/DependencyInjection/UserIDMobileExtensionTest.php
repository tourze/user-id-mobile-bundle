<?php

namespace Tourze\UserIDMobileBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;
use Tourze\UserIDMobileBundle\DependencyInjection\UserIDMobileExtension;

/**
 * @internal
 */
#[CoversClass(UserIDMobileExtension::class)]
final class UserIDMobileExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    private UserIDMobileExtension $extension;

    private ContainerBuilder $container;

    protected function setUp(): void
    {
        parent::setUp();

        $this->extension = new UserIDMobileExtension();
        $this->container = new ContainerBuilder();
        $this->container->setParameter('kernel.environment', 'test');
    }

    protected function getExtension(): UserIDMobileExtension
    {
        return $this->extension;
    }

    protected function getContainer(): ContainerBuilder
    {
        return $this->container;
    }

    public function testLoad(): void
    {
        $this->extension->load([], $this->container);

        $this->assertTrue($this->container->hasDefinition('Tourze\UserIDMobileBundle\Repository\MobileIdentityRepository'));
        $this->assertTrue($this->container->hasDefinition('Tourze\UserIDMobileBundle\Service\UserIdentityMobileService'));
    }
}
