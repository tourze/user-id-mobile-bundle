<?php

namespace Tourze\UserIDMobileBundle\Tests\Integration;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Tourze\UserIDBundle\Service\UserIdentityService;
use Tourze\UserIDBundle\UserIDBundle;
use Tourze\UserIDMobileBundle\Repository\MobileIdentityRepository;
use Tourze\UserIDMobileBundle\Service\UserIdentityMobileService;
use Tourze\UserIDMobileBundle\UserIDMobileBundle;

class IntegrationTestKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new DoctrineBundle(),
            new UserIDBundle(),
            new UserIDMobileBundle(),
        ];
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->loadFromExtension('framework', [
            'test' => true,
            'secret' => 'test',
            'http_method_override' => false,
            'handle_all_throwables' => true,
            'validation' => [
                'email_validation_mode' => 'html5',
            ],
            'php_errors' => [
                'log' => true,
            ],
            'uid' => [
                'default_uuid_version' => 7,
                'time_based_uuid_version' => 7,
            ],
        ]);

        $container->loadFromExtension('doctrine', [
            'dbal' => [
                'driver' => 'pdo_sqlite',
                'path' => ':memory:',
            ],
            'orm' => [
                'auto_generate_proxy_classes' => true,
                'auto_mapping' => true,
                'controller_resolver' => [
                    'auto_mapping' => false,
                ],
                'mappings' => [
                    'UserIDMobileBundle' => [
                        'is_bundle' => true,
                        'type' => 'attribute',
                        'dir' => 'Entity',
                        'prefix' => 'Tourze\UserIDMobileBundle\Entity',
                    ],
                ],
            ],
        ]);
        
        // 手动注册测试所需的服务
        $container->register(UserIdentityService::class, UserIdentityService::class)
            ->setPublic(true);
            
        $container->register(MobileIdentityRepository::class, MobileIdentityRepository::class)
            ->setAutowired(true)
            ->setPublic(true);
            
        $container->register(UserIdentityMobileService::class, UserIdentityMobileService::class)
            ->setAutowired(true)
            ->setPublic(true)
            ->setDecoratedService(UserIdentityService::class);
    }
} 