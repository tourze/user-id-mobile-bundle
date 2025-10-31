<?php

namespace Tourze\UserIDMobileBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Tourze\UserIDMobileBundle\Entity\MobileIdentity;

class MobileIdentityFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $mobileIdentity1 = new MobileIdentity();
        $mobileIdentity1->setMobileNumber('13800138001');
        $manager->persist($mobileIdentity1);

        $mobileIdentity2 = new MobileIdentity();
        $mobileIdentity2->setMobileNumber('13800138002');
        $manager->persist($mobileIdentity2);

        $mobileIdentity3 = new MobileIdentity();
        $mobileIdentity3->setMobileNumber('13800138003');
        $manager->persist($mobileIdentity3);

        $manager->flush();
    }
}
