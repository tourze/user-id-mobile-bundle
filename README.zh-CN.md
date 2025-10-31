# User ID Mobile Bundle

[![PHP 版本](https://img.shields.io/badge/php-%5E8.1-blue)](https://packagist.org/packages/tourze/user-id-mobile-bundle)
[![许可证](https://img.shields.io/badge/license-MIT-green)](LICENSE)
[![构建状态](https://img.shields.io/badge/build-passing-brightgreen)](https://github.com/tourze/php-monorepo)
[![代码覆盖率](https://img.shields.io/badge/coverage-100%25-brightgreen)](https://github.com/tourze/php-monorepo)

[English](README.md) | [中文](README.zh-CN.md)

用于管理用户身份识别系统中手机身份的 Symfony Bundle。

## 功能特性

- **手机身份实体**：存储与用户账户关联的手机号码
- **仓储模式**：为手机身份提供数据访问层
- **服务层**：与用户身份服务系统集成
- **Doctrine 集成**：使用 Doctrine ORM 进行数据持久化
- **Symfony 集成**：完全集成到 Symfony 框架中
- **验证约束**：完整的手机号码验证约束支持

## 安装

```bash
composer require tourze/user-id-mobile-bundle
```

## 快速开始

### 1. 启用 Bundle

在 `config/bundles.php` 中添加 bundle：

```php
return [
    // ...
    Tourze\UserIDMobileBundle\UserIDMobileBundle::class => ['all' => true],
];
```

### 2. 更新数据库架构

运行 doctrine 迁移以创建手机身份表：

```bash
php bin/console doctrine:migrations:migrate
```

### 3. 基本使用

```php
use Tourze\UserIDMobileBundle\Entity\MobileIdentity;
use Tourze\UserIDMobileBundle\Repository\MobileIdentityRepository;
use Tourze\UserIDMobileBundle\Service\UserIdentityMobileService;

// 创建新的手机身份
$mobileIdentity = new MobileIdentity();
$mobileIdentity->setMobileNumber('13800138000');
$mobileIdentity->setUser($user);

// 根据手机号码查找手机身份
$mobileIdentity = $userIdentityMobileService->findByType('mobile', '13800138000');

// 查找用户的所有手机身份
$identities = $userIdentityMobileService->findByUser($user);
```

## 配置

该 bundle 使用默认配置，无需额外设置。手机身份表 (`ims_user_identity_mobile`) 
将在运行迁移时自动创建。

## 实体结构

`MobileIdentity` 实体包含：
- `id`：主键（Snowflake ID）
- `mobileNumber`：手机号码（最大 20 个字符）
- `user`：用户实体引用
- `createTime`：创建时间戳
- `updateTime`：更新时间戳
- `createdBy`：创建者引用
- `updatedBy`：更新者引用

## 服务集成

`UserIdentityMobileService` 装饰基础 `UserIdentityService` 以添加手机身份支持：

- `findByType(string $type, string $value)`：根据类型和值查找身份
- `findByUser(UserInterface $user)`：查找用户的所有身份

## 高级用法

### 验证约束

手机号码字段包含完整的验证约束：

```php
#[Assert\NotBlank(message: '手机号码不能为空')]
#[Assert\Length(max: 20, maxMessage: '手机号码长度不能超过 {{ limit }} 个字符')]
#[Assert\Regex(
    pattern: '/^[1-9]\d{10}$/',
    message: '请输入有效的中国大陆手机号码'
)]
```

### 自定义仓储查询

扩展仓储以实现自定义查询：

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

### 服务装饰

服务自动装饰以集成到身份系统中：

```php
// services.yaml
services:
    Tourze\UserIDMobileBundle\Service\UserIdentityMobileService:
        decorates: 'Tourze\UserIDBundle\Service\UserIdentityService'
        decoration_priority: 10
```

## 测试

运行测试套件：

```bash
vendor/bin/phpunit packages/user-id-mobile-bundle/tests
```

## 依赖要求

此 bundle 需要：

- PHP 8.1 或更高版本
- Symfony 6.4 或更高版本
- Doctrine ORM 3.0 或更高版本
- Doctrine DBAL 4.0 或更高版本

内部依赖：
- `tourze/bundle-dependency`：Bundle 依赖管理
- `tourze/doctrine-snowflake-bundle`：Snowflake ID 生成
- `tourze/doctrine-timestamp-bundle`：时间戳管理
- `tourze/doctrine-user-bundle`：用户管理
- `tourze/user-id-bundle`：用户身份系统

## 贡献指南

我们欢迎贡献！请查看我们的[贡献指南](../../CONTRIBUTING.md)了解详细信息：

- 报告问题
- 提交拉取请求
- 代码风格要求
- 测试标准

### 开发环境搭建

1. Fork 并克隆仓库
2. 安装依赖：`composer install`
3. 运行测试：`vendor/bin/phpunit packages/user-id-mobile-bundle/tests`
4. 运行静态分析：`vendor/bin/phpstan analyse packages/user-id-mobile-bundle`

## 安全漏洞

如果您发现安全漏洞，请发送邮件至 security@tourze.cn，而不是使用 issue 跟踪器。

## 许可证

此 bundle 基于 MIT 许可证发布。详情请参阅 [LICENSE](LICENSE) 文件。
