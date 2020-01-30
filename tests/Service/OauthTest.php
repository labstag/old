<?php

namespace Labstag\Tests\Service;

use Labstag\Entity\OauthConnectUser;
use Labstag\Lib\GenericProviderLib;
use Labstag\Lib\ServiceTestLib;
use Labstag\Repository\OauthConnectUserRepository;
use Labstag\Service\OauthService;

/**
 * @internal
 * @coversNothing
 */
class OauthTest extends ServiceTestLib
{

    /**
     * @var OauthService
     */
    private $service;

    /**
     * @var OauthConnectUserRepository
     */
    private $repository;

    public function setUp(): void
    {
        parent::setUp();
        /** @var OauthService $service */
        $service       = self::$container->get(OauthService::class);
        $this->service = $service;
        /** @var OauthConnectUserRepository $repository */
        $repository = $this->entityManager->getRepository(
            OauthConnectUser::class
        );

        $this->repository = $repository;
    }

    public function testgetIdentity(): void
    {
        $service = $this->service;
        $empty   = $service->getIdentity(null, null);
        $this->AssertNull($empty);
        $random = $this->repository->findOneRandom();
        if ($random instanceof OauthConnectUser) {
            $identity = $service->getIdentity([], '');
            $this->AssertNull($identity);
        }
    }

    public function testgetActivedProvider(): void
    {
        $service = $this->service;
        $empty   = $service->getActivedProvider(null);
        $this->assertFalse($empty);
        $falseProvider = $service->getActivedProvider(md5('gitlab'));
        $this->assertFalse($falseProvider);
        $gitlab = $service->getActivedProvider('gitlab');
        $this->assertTrue($gitlab);
    }

    public function testsetProvider(): void
    {
        $service = $this->service;
        $empty   = $service->setProvider(null);
        $this->AssertNull($empty);
        /** @var GenericProviderLib $gitlab */
        $gitlab = $service->setProvider('gitlab');
        $this->assertSame(get_class($gitlab), GenericProviderLib::class);
    }
}
