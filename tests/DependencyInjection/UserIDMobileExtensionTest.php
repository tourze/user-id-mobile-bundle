<?php

namespace Tourze\UserIDMobileBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\UserIDMobileBundle\DependencyInjection\UserIDMobileExtension;

class UserIDMobileExtensionTest extends TestCase
{
    public function testLoad(): void
    {
        $container = new ContainerBuilder();
        $extension = new UserIDMobileExtension();
        
        $extension->load([], $container);
        
        $this->assertTrue($container->hasDefinition('Tourze\UserIDMobileBundle\Repository\MobileIdentityRepository'));
        $this->assertTrue($container->hasDefinition('Tourze\UserIDMobileBundle\Service\UserIdentityMobileService'));
    }
} 