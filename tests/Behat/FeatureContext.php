<?php

namespace App\Tests\Behat;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Behat\Mink\Exception\DriverException;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behat\MinkExtension\Context\RawMinkContext;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;

final class FeatureContext extends RawMinkContext
{
    private static $app;
    private static $dbalConnection;
    
    public function __construct(KernelInterface $kernel, EntityManagerInterface $em)
    {
        self::$app = new Application($kernel);
        self::$app->setAutoExit(false);
        self::$dbalConnection = $em->getConnection();
    }
    
    /**
     * @BeforeSuite
     */
    public static function initializeEnvironment(): void
    {
        echo 'Initializing ...';
    }
    
    /**
     * @AfterSuite
     *
     * @throws Exception
     */
    public static function restoreEnvironment(): void
    {
        self::loadFixtures();
    }
    
    /**
     * @Given the environment with fixtures
     *
     * @throws Exception
     */
    public static function loadFixtures(): void
    {
        $arg = new ArrayInput(
            [
                'command' => 'doctrine:fixtures:load',
                '--no-interaction' => true,
            ]
        );
        
        $output = new BufferedOutput();
        $result = self::$app->run($arg, $output);
        
        if (0 !== $result) {
            $error = $output->fetch();
            echo 'Error detected: '.$error;
        } else {
            echo 'Database restored ... '.PHP_EOL;
        }
    }
    
    /**
     * @Given the environment is clean
     *
     * @throws Exception
     */
    public static function cleanEnvironment(): void
    {
        $tables = self::$dbalConnection->getSchemaManager()->listTables();
        
        foreach ($tables as $theTable) {
            $tableName = $theTable->getName();
            
            if ('migration_versions' === $tableName) {
                continue;
            }
            
            self::$dbalConnection->executeQuery('TRUNCATE '.$tableName.' CASCADE');
        }
        
        echo 'Cleaned ...'.PHP_EOL;
    }
    
    /**
     * @Given /^the response json should have a "([^"]*)" key$/
     *
     * @throws DriverException
     * @throws UnsupportedDriverActionException
     * @throws AssertionFailedException
     */
    public function theResponseJsonShouldHaveAKey(string $keyName): void
    {
        $content = json_decode($this->getResponseContent(), true);
        
        Assertion::keyExists($content, $keyName);
    }
    
    /**
     * @Given /^the response json should contains (?P<numberOfElements>\d+) elements$/
     *
     * @throws DriverException
     * @throws UnsupportedDriverActionException
     * @throws AssertionFailedException
     */
    public function theResponseJsonShouldContainsElements(int $numberOfElements): void
    {
        $content = json_decode($this->getResponseContent(), true);
        
        Assertion::count($content, $numberOfElements);
    }
    
    /**
     * @throws DriverException
     * @throws UnsupportedDriverActionException
     */
    private function getResponseContent(): string
    {
        return $this->getSession()->getDriver()->getContent();
    }
}
