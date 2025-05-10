<?php

namespace Tourze\UserIDMobileBundle\Tests\Integration;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tourze\UserIDMobileBundle\Entity\MobileIdentity;

class EntityMappingTest extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return IntegrationTestKernel::class;
    }

    public function testEntityMapping(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine')->getManager();
        
        $metadata = $entityManager->getClassMetadata(MobileIdentity::class);
        
        $this->assertEquals('ims_user_identity_mobile', $metadata->getTableName());
        $this->assertTrue($metadata->hasField('mobileNumber'));
        $this->assertTrue($metadata->hasAssociation('user'));
        
        $this->assertEquals('id', $metadata->getSingleIdentifierFieldName());
        // ID 生成策略因 Doctrine 版本而异
        $this->assertNotNull($metadata->generatorType);
    }
} 