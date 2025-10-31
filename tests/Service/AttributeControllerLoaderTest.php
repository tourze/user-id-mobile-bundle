<?php

namespace Tourze\UserIDMobileBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Routing\RouteCollection;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use Tourze\UserIDMobileBundle\Service\AttributeControllerLoader;

/**
 * @internal
 */
#[CoversClass(AttributeControllerLoader::class)]
#[RunTestsInSeparateProcesses]
final class AttributeControllerLoaderTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
    }

    public function testLoadReturnsRouteCollection(): void
    {
        $loader = self::getService(AttributeControllerLoader::class);
        $result = $loader->load('resource');

        $this->assertInstanceOf(RouteCollection::class, $result);
    }

    public function testSupportsReturnsFalse(): void
    {
        $loader = self::getService(AttributeControllerLoader::class);
        $result = $loader->supports('resource');

        $this->assertFalse($result);
    }

    public function testAutoloadReturnsRouteCollection(): void
    {
        $loader = self::getService(AttributeControllerLoader::class);
        $result = $loader->autoload();

        $this->assertInstanceOf(RouteCollection::class, $result);
    }

    public function testAutoloadAndLoadReturnSameCollection(): void
    {
        $loader = self::getService(AttributeControllerLoader::class);
        $autoloadResult = $loader->autoload();
        $loadResult = $loader->load('resource');

        $this->assertSame($autoloadResult, $loadResult);
    }

    public function testLoaderIsProperlyConfigured(): void
    {
        $loader = self::getService(AttributeControllerLoader::class);
        $this->assertInstanceOf(AttributeControllerLoader::class, $loader);
    }
}
