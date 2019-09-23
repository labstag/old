<?php

namespace Labstag\Tests\Service;

use Labstag\Entity\OauthConnectUser;
use Labstag\Lib\GenericProviderLib;
use Labstag\Lib\ServiceTestLib;
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

    public function setUp(): void
    {
        parent::setUp();
        $this->service    = self::$container->get(OauthService::class);
        $this->repository = $this->entityManager->getRepository(
            OauthConnectUser::class
        );
    }

    public function testgetIdentity()
    {
        $service = $this->service;
        $empty   = $service->getIdentity(null, null);
        $this->AssertNull($empty);
        $random = $this->repository->findOneRandom();
        if ($random instanceof OauthConnectUser) {
            $identity = $service->getIdentity('', '');
            $this->AssertNull($identity);
        }
    }

    public function testgetActivedProvider()
    {
        $service = $this->service;
        $empty   = $service->getActivedProvider(null);
        $this->assertFalse($empty);
        $falseProvider = $service->getActivedProvider(md5('gitlab'));
        $this->assertFalse($falseProvider);
        $gitlab = $service->getActivedProvider('gitlab');
        $this->assertTrue($gitlab);
    }

    public function testsetProvider()
    {
        $service = $this->service;
        $empty   = $service->setProvider(null);
        $this->AssertNull($empty);
        $gitlab = $service->setProvider('gitlab');
        $this->assertSame(get_class($gitlab), GenericProviderLib::class);
    }
}
