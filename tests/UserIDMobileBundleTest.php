<?php

declare(strict_types=1);

namespace Tourze\UserIDMobileBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;
use Tourze\UserIDMobileBundle\UserIDMobileBundle;

/**
 * @internal
 */
#[CoversClass(UserIDMobileBundle::class)]
#[RunTestsInSeparateProcesses]
final class UserIDMobileBundleTest extends AbstractBundleTestCase
{
}
