<?php

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Persistence\ObjectManager;

class FeatureContext implements Context
{
    public function __construct(Registry $doctrine) {
        /** @var ObjectManager[] $managers */
        $managers = $doctrine->getManagers();

        foreach ($managers as $manager) {
            if ($manager instanceof EntityManagerInterface) {
                $schemaTool = new SchemaTool($manager);
                $schemaTool->dropDatabase();
                $schemaTool->createSchema($manager->getMetadataFactory()->getAllMetadata());
            }
        }
    }
}
