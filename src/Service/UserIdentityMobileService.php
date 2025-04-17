<?php

namespace Tourze\UserIDMobileBundle\Service;

use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\UserIDBundle\Contracts\UserIdentityInterface;
use Tourze\UserIDBundle\Service\UserIdentityService;
use Tourze\UserIDMobileBundle\Entity\MobileIdentity;
use Tourze\UserIDMobileBundle\Repository\MobileIdentityRepository;

#[AsDecorator(decorates: UserIdentityService::class)]
class UserIdentityMobileService implements UserIdentityService
{
    public function __construct(
        private readonly MobileIdentityRepository $mobileIdentityRepository,
        #[AutowireDecorated] private readonly UserIdentityService $inner,
    ) {
    }

    public function findByType(string $type, string $value): ?UserIdentityInterface
    {
        // 手机号码
        if (MobileIdentity::IDENTITY_TYPE === $type) {
            $result = $this->mobileIdentityRepository?->findOneBy(['mobileNumber' => $value]);
            if ($result) {
                return $result;
            }
        }

        return $this->inner->findByType($type, $value);
    }

    public function findByUser(UserInterface $user): iterable
    {
        foreach ($this->mobileIdentityRepository->findBy(['user' => $user]) as $item) {
            yield $item;
        }
        foreach ($this->inner->findByUser($user) as $item) {
            yield $item;
        }
    }
}
