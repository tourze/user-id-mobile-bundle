<?php

namespace Tourze\UserIDMobileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
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
    #[Assert\NotBlank(message: '手机号码不能为空')]
    #[Assert\Length(
        max: 20,
        maxMessage: '手机号码长度不能超过 {{ limit }} 个字符'
    )]
    #[Assert\Regex(
        pattern: '/^[1-9]\d{10}$/',
        message: '请输入有效的中国大陆手机号码'
    )]
    private string $mobileNumber;

    #[ORM\ManyToOne(targetEntity: UserInterface::class)]
    #[ORM\JoinColumn(name: 'user_id', nullable: true, onDelete: 'SET NULL')]
    private ?UserInterface $user = null;

    public function __toString(): string
    {
        return $this->mobileNumber ?? '';
    }

    public function getMobileNumber(): string
    {
        return $this->mobileNumber;
    }

    public function setMobileNumber(string $mobileNumber): void
    {
        $this->mobileNumber = $mobileNumber;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }

    public function getIdentityValue(): string
    {
        return $this->getMobileNumber();
    }

    public function getIdentityType(): string
    {
        return self::IDENTITY_TYPE;
    }

    /**
     * @return \Generator<Identity>
     */
    public function getIdentityArray(): \Traversable
    {
        yield new Identity((string) $this->getId(), $this->getIdentityType(), $this->getIdentityValue(), [
            'createTime' => $this->getCreateTime()?->format('Y-m-d H:i:s'),
            'updateTime' => $this->getUpdateTime()?->format('Y-m-d H:i:s'),
        ]);
    }

    public function getAccounts(): array
    {
        return [];
    }
}
