<?php

namespace App\Tests\Functional;

use Assert\Assertion;
use Behat\MinkExtension\Context\RawMinkContext;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\HttpKernel\KernelInterface;

class FeatureContext extends RawMinkContext
{
    private KernelInterface $kernel;

    public function __construct(KernelInterface $kernel, EntityManagerInterface $manager) {
        $this->kernel = $kernel;

        if ($manager instanceof EntityManagerInterface) {
            $schemaTool = new SchemaTool($manager);
            $schemaTool->dropDatabase();
            $schemaTool->createSchema($manager->getMetadataFactory()->getAllMetadata());
        }
    }

    /**
     * @Given /^the response json should contains (?P<numberOfElements>\d+) elements$/
     */
    public function theResponseJsonShouldContainsElements(int $numberOfElements): void
    {
        $content = json_decode($this->getResponseContent(), true);
        Assertion::count($content, $numberOfElements);
    }

    private function getResponseContent(): string
    {
        return $this->getSession()->getDriver()->getContent();
    }

}
