# User ID Mobile Bundle

[![PHP Version](https://img.shields.io/badge/php-%5E8.1-blue)]
(https://packagist.org/packages/tourze/user-id-mobile-bundle)
[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen)](https://github.com/tourze/php-monorepo)
[![Code Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen)](https://github.com/tourze/php-monorepo)

[English](README.md) | [中文](README.zh-CN.md)

A Symfony bundle for managing mobile phone identity within the user identification system.

## Features

- **Mobile Identity Entity**: Stores mobile phone numbers linked to user accounts
- **Repository Pattern**: Provides data access layer for mobile identities
- **Service Layer**: Integrates with the user identity service system
- **Doctrine Integration**: Uses Doctrine ORM for data persistence
- **Symfony Integration**: Fully integrated with Symfony framework
- **Validation**: Comprehensive phone number validation with constraints

## Installation

```bash
composer require tourze/user-id-mobile-bundle
```

## Quick Start

### 1. Enable the Bundle

Add the bundle to your `config/bundles.php`:

```php
return [
    // ...
    Tourze\UserIDMobileBundle\UserIDMobileBundle::class => ['all' => true],
];
```

### 2. Update Database Schema

Run the doctrine migrations to create the mobile identity table:

```bash
php bin/console doctrine:migrations:migrate
```

### 3. Basic Usage

```php
use Tourze\UserIDMobileBundle\Entity\MobileIdentity;
use Tourze\UserIDMobileBundle\Repository\MobileIdentityRepository;
use Tourze\UserIDMobileBundle\Service\UserIdentityMobileService;

// Create a new mobile identity
$mobileIdentity = new MobileIdentity();
$mobileIdentity->setMobileNumber('13800138000');
$mobileIdentity->setUser($user);

// Find mobile identity by phone number
$mobileIdentity = $userIdentityMobileService->findByType('mobile', '13800138000');

// Find all mobile identities for a user
$identities = $userIdentityMobileService->findByUser($user);
```

## Configuration

The bundle uses default configuration and doesn't require additional setup. 
The mobile identity table (`ims_user_identity_mobile`) will be created automatically 
when you run migrations.

## Entity Structure

The `MobileIdentity` entity includes:
- `id`: Primary key (Snowflake ID)
- `mobileNumber`: Mobile phone number (max 20 characters)
- `user`: Reference to the user entity
- `createTime`: Creation timestamp
- `updateTime`: Update timestamp
- `createdBy`: Creator reference
- `updatedBy`: Updater reference

## Service Integration

The `UserIdentityMobileService` decorates the base `UserIdentityService` to add 
mobile phone identity support:

- `findByType(string $type, string $value)`: Find identity by type and value
- `findByUser(UserInterface $user)`: Find all identities for a user

## Advanced Usage

### Validation Constraints

The mobile number field includes comprehensive validation:

```php
#[Assert\NotBlank(message: '手机号码不能为空')]
#[Assert\Length(max: 20, maxMessage: '手机号码长度不能超过 {{ limit }} 个字符')]
#[Assert\Regex(
    pattern: '/^[1-9]\d{10}$/',
    message: '请输入有效的中国大陆手机号码'
)]
```

### Custom Repository Queries

Extend the repository for custom queries:

```php
use Tourze\UserIDMobileBundle\Repository\MobileIdentityRepository;

class CustomMobileIdentityRepository extends MobileIdentityRepository
{
    public function findByAreaCode(string $areaCode): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.mobileNumber LIKE :pattern')
            ->setParameter('pattern', $areaCode . '%')
            ->getQuery()
            ->getResult();
    }
}
```

### Service Decoration

The service is automatically decorated to integrate with the identity system:

```php
// services.yaml
services:
    Tourze\UserIDMobileBundle\Service\UserIdentityMobileService:
        decorates: 'Tourze\UserIDBundle\Service\UserIdentityService'
        decoration_priority: 10
```

## Testing

Run the test suite:

```bash
vendor/bin/phpunit packages/user-id-mobile-bundle/tests
```

## Dependencies

This bundle requires:

- PHP 8.1 or higher
- Symfony 6.4 or higher
- Doctrine ORM 3.0 or higher
- Doctrine DBAL 4.0 or higher

Internal dependencies:
- `tourze/bundle-dependency`: Bundle dependency management
- `tourze/doctrine-snowflake-bundle`: Snowflake ID generation
- `tourze/doctrine-timestamp-bundle`: Timestamp management
- `tourze/doctrine-user-bundle`: User management
- `tourze/user-id-bundle`: User identity system

## Contributing

We welcome contributions! Please see our [contributing guidelines](../../CONTRIBUTING.md) for details on:

- Reporting issues
- Submitting pull requests
- Code style requirements
- Testing standards

### Development Setup

1. Fork and clone the repository
2. Install dependencies: `composer install`
3. Run tests: `vendor/bin/phpunit packages/user-id-mobile-bundle/tests`
4. Run static analysis: `vendor/bin/phpstan analyse packages/user-id-mobile-bundle`

## Security Vulnerabilities

If you discover a security vulnerability, please send an email to security@tourze.cn instead of using the issue tracker.

## License

This bundle is released under the MIT License. See the [LICENSE](LICENSE) file for details.