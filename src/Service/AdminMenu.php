<?php

declare(strict_types=1);

namespace Tourze\UserIDMobileBundle\Service;

use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;
use Tourze\UserIDMobileBundle\Entity\MobileIdentity;

/**
 * 手机身份管理后台菜单提供者
 */
#[Autoconfigure(public: true)]
readonly class AdminMenu implements MenuProviderInterface
{
    public function __construct(
        private LinkGeneratorInterface $linkGenerator,
    ) {
    }

    public function __invoke(ItemInterface $item): void
    {
        if (null === $item->getChild('用户管理')) {
            $item->addChild('用户管理');
        }

        $userMenu = $item->getChild('用户管理');
        if (null === $userMenu) {
            return;
        }

        // 添加手机身份管理菜单
        $userMenu->addChild('手机身份管理')
            ->setUri($this->linkGenerator->getCurdListPage(MobileIdentity::class))
            ->setAttribute('icon', 'fas fa-mobile-alt')
        ;
    }
}
