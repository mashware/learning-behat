<?php

namespace App\Tests\Behat;

use App\Kernel;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\HttpFoundation\Request;

class FeatureContext implements Context
{
    private Kernel $kernel;
    private $response;

    public function __construct(Kernel $kernel, EntityManagerInterface $manager) {
        $this->kernel = $kernel;
        $this->response = null;

        if ($manager instanceof EntityManagerInterface) {
            $schemaTool = new SchemaTool($manager);
            $schemaTool->dropDatabase();
            $schemaTool->createSchema($manager->getMetadataFactory()->getAllMetadata());
        }
    }

    /**
     * @When a demo scenario sends a request to :path
     */
    public function aDemoScenarioSendsARequestTo(string $path)
    {
        $this->response = $this->kernel->handle(Request::create($path, 'GET'));
    }

    /**
     * @Then the response should be received
     */
    public function theResponseShouldBeReceived()
    {
        if ($this->response === null) {
            throw new \RuntimeException('No response received');
        }
    }
}
