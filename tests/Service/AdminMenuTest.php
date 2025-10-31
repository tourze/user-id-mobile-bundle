<?php

namespace Tourze\UserIDMobileBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminMenuTestCase;
use Tourze\UserIDMobileBundle\Service\AdminMenu;

/**
 * @internal
 */
#[CoversClass(AdminMenu::class)]
#[RunTestsInSeparateProcesses]
final class AdminMenuTest extends AbstractEasyAdminMenuTestCase
{
    protected function onSetUp(): void
    {
    }

    protected function getMenuProviderClass(): string
    {
        return AdminMenu::class;
    }

    public function testAdminMenuCanBeInstantiated(): void
    {
        $adminMenu = self::getService(AdminMenu::class);
        $this->assertInstanceOf(AdminMenu::class, $adminMenu);
    }

    public function testAdminMenuIsCallable(): void
    {
        $adminMenu = self::getService(AdminMenu::class);
        $this->assertIsCallable($adminMenu);
    }
}
