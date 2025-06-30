<?php

namespace Tourze\UserIDMobileBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use Tourze\UserIDBundle\Contracts\IdentityInterface;
use Tourze\UserIDBundle\Model\Identity;
use Tourze\UserIDMobileBundle\Repository\MobileIdentityRepository;

#[ORM\Entity(repositoryClass: MobileIdentityRepository::class)]
#[ORM\Table(name: 'ims_user_identity_mobile', options: ['comment' => '手机身份'])]
class MobileIdentity implements IdentityInterface, \Stringable
{
    use SnowflakeKeyAware;
    use TimestampableAware;
    use BlameableAware;
    public const IDENTITY_TYPE = 'mobile';

    #[ORM\Column(length: 20, nullable: false, options: ['comment' => '手机号码'])]
    private string $mobileNumber;

    #[ORM\ManyToOne]
    private ?UserInterface $user = null;

    public function __toString(): string
    {
        return $this->mobileNumber ?? '';
    }

    public function getMobileNumber(): string
    {
        return $this->mobileNumber;
    }

    public function setMobileNumber(string $mobileNumber): static
    {
        $this->mobileNumber = $mobileNumber;

        return $this;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): static
    {
        $this->user = $user;

        return $this;
    }public function getIdentityValue(): string
    {
        return $this->getMobileNumber();
    }

    public function getIdentityType(): string
    {
        return self::IDENTITY_TYPE;
    }

    public function getIdentityArray(): \Traversable
    {
        yield new Identity($this->getId(), $this->getIdentityType(), $this->getIdentityValue(), [
            'createTime' => $this->getCreateTime()?->format('Y-m-d H:i:s'),
            'updateTime' => $this->getUpdateTime()?->format('Y-m-d H:i:s'),
        ]);
    }

    public function getAccounts(): array
    {
        return [];
    }
}
