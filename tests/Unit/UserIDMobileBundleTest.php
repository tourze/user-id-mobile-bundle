<?php

namespace Tourze\UserIDMobileBundle\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tourze\UserIDMobileBundle\UserIDMobileBundle;

class UserIDMobileBundleTest extends TestCase
{
    public function testBundleIsInstantiable(): void
    {
        $bundle = new UserIDMobileBundle();
        
        $this->assertInstanceOf(UserIDMobileBundle::class, $bundle);
    }
}