<?php

namespace App\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;

abstract class FixtureTestCase extends WebTestCase
{
    /**
     * @var ORMExecutor
     */
    private $fixtureExecutor;

    /**
     * @var ContainerAwareLoader
     */
    private $fixtureLoader;

    protected function setUp()
    {
        static::bootKernel();
    }

    /**
     * @param FixtureInterface $fixture
     */
    protected function addFixture(FixtureInterface $fixture): void
    {
        $this->getFixtureLoader()->addFixture($fixture);
    }

    protected function executeFixtures(): void
    {
        $this->getFixtureExecutor()->execute($this->getFixtureLoader()->getFixtures());
    }

    /**
     * @return ORMExecutor
     */
    private function getFixtureExecutor(): ORMExecutor
    {
        if (!$this->fixtureExecutor) {
            /** @var EntityManager $entityManager */
            $entityManager = static::$kernel->getContainer()->get('doctrine')->getManager();
            $this->fixtureExecutor = new ORMExecutor($entityManager, new ORMPurger($entityManager));
        }
        return $this->fixtureExecutor;
    }

    /**
     * @return ContainerAwareLoader
     */
    private function getFixtureLoader(): ContainerAwareLoader
    {
        if (!$this->fixtureLoader) {
            $this->fixtureLoader = new ContainerAwareLoader(static::$kernel->getContainer());
        }
        return $this->fixtureLoader;
    }
}
