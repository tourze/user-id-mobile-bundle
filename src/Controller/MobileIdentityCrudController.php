<?php

declare(strict_types=1);

namespace Tourze\UserIDMobileBundle\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Tourze\UserIDMobileBundle\Entity\MobileIdentity;

/**
 * 手机身份管理控制器
 */
#[AdminCrud(routePath: '/user-id-mobile/mobile-identity', routeName: 'user_id_mobile_mobile_identity')]
final class MobileIdentityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MobileIdentity::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')
                ->hideOnForm(),

            TextField::new('mobileNumber', '手机号码')
                ->setRequired(true)
                ->setHelp('11位中国大陆手机号码')
                ->setMaxLength(20),

            AssociationField::new('user', '关联用户')
                ->setRequired(false)
                ->setHelp('与该手机号码关联的用户账户'),

            TextField::new('createdBy', '创建者')
                ->hideOnForm()
                ->setHelp('创建此记录的用户标识'),

            TextField::new('updatedBy', '更新者')
                ->hideOnForm()
                ->setHelp('最后更新此记录的用户标识'),

            DateTimeField::new('createTime', '创建时间')
                ->hideOnForm()
                ->setFormat('yyyy-MM-dd HH:mm:ss'),

            DateTimeField::new('updateTime', '更新时间')
                ->hideOnForm()
                ->setFormat('yyyy-MM-dd HH:mm:ss'),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('mobileNumber')
            ->add('user')
            ->add('createTime')
            ->add('updateTime')
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('手机身份')
            ->setEntityLabelInPlural('手机身份')
            ->setPageTitle('index', '手机身份管理')
            ->setPageTitle('new', '新增手机身份')
            ->setPageTitle('edit', '编辑手机身份')
            ->setPageTitle('detail', '手机身份详情')
        ;
    }
}
