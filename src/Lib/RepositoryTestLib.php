<?php

namespace Labstag\Lib;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class RepositoryTestLib extends KernelTestCase
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        $kernel = self::bootKernel();

        $container           = $kernel->getContainer();
        $this->container     = $container;
        $this->entityManager = $container->get('doctrine')->getManager();
    }
}
