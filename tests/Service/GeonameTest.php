<?php

namespace Labstag\Tests\Service;

use Labstag\Lib\ServiceTestLib;
use Labstag\Service\GeonameService;

/**
 * @internal
 * @coversNothing
 */
class GeonameTest extends ServiceTestLib
{

    /**
     * @var GeonameService
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();
        /** @var GeonameService $service */
        $service       = self::$container->get(GeonameService::class);
        $this->service = $service;
    }

    public function testastergdem(): void
    {
        /** @var null $data */
        $data = $this->service->astergdem(null, null);
        $this->assertNull($data);
    }

    public function testchildren(): void
    {
        /** @var null $data */
        $data = $this->service->children(null, null);
        $this->assertNull($data);
    }

    public function testcountains(): void
    {
        /** @var null $data */
        $data = $this->service->countains(null, null);
        $this->assertNull($data);
    }

    public function testcountryCode(): void
    {
        /** @var null $data */
        $data = $this->service->countryCode(null, null);
        $this->assertNull($data);
    }

    public function testcountryInfo(): void
    {
        /** @var null $data */
        $data = $this->service->countryInfo(null, null);
        $this->assertNull($data);
    }

    public function testcountrySubdivision(): void
    {
        /** @var null $data */
        $data = $this->service->countrySubdivision(null, null);
        $this->assertNull($data);
    }

    public function testearthquakes(): void
    {
        /** @var null $data */
        $data = $this->service->earthquakes(null, null);
        $this->assertNull($data);
    }

    public function testextentedFindNearby(): void
    {
        /** @var null $data */
        $data = $this->service->extentedFindNearby(null, null);
        $this->assertNull($data);
    }

    public function testfindNearby(): void
    {
        /** @var null $data */
        $data = $this->service->findNearby(null, null);
        $this->assertNull($data);
    }

    public function testfindNearbyPlaceName(): void
    {
        /** @var null $data */
        $data = $this->service->findNearbyPlaceName(null, null);
        $this->assertNull($data);
    }

    public function testfindNearbyPostalCodes(): void
    {
        /** @var null $data */
        $data = $this->service->findNearbyPostalCodes(null, null);
        $this->assertNull($data);
    }

    public function testfindNearbyStreets(): void
    {
        /** @var null $data */
        $data = $this->service->findNearbyStreets(null, null);
        $this->assertNull($data);
    }

    public function testfindNearbyStreetsoSM(): void
    {
        /** @var null $data */
        $data = $this->service->findNearbyStreetsoSM(null, null);
        $this->assertNull($data);
    }

    public function testfindNearByWeather(): void
    {
        /** @var null $data */
        $data = $this->service->findNearByWeather(null, null);
        $this->assertNull($data);
    }

    public function testfindNearbyWikipedia(): void
    {
        /** @var null $data */
        $data = $this->service->findNearbyWikipedia(null, null);
        $this->assertNull($data);
    }

    public function testfindNearestAddress(): void
    {
        /** @var null $data */
        $data = $this->service->findNearestAddress(null, null);
        $this->assertNull($data);
    }

    public function testfindNearestIntersection(): void
    {
        /** @var null $data */
        $data = $this->service->findNearestIntersection(null, null);
        $this->assertNull($data);
    }

    public function testfindNearbyPOIsOSM(): void
    {
        /** @var null $data */
        $data = $this->service->findNearbyPOIsOSM(null, null);
        $this->assertNull($data);
    }

    public function testaddress(): void
    {
        /** @var null $data */
        $data = $this->service->address(null, null);
        $this->assertNull($data);
    }

    public function testgeoCodeAddress(): void
    {
        /** @var null $data */
        $data = $this->service->geoCodeAddress(null, null);
        $this->assertNull($data);
    }

    public function testgetStreetNameLookup(): void
    {
        /** @var null $data */
        $data = $this->service->getStreetNameLookup(null, null);
        $this->assertNull($data);
    }

    public function testget(): void
    {
        /** @var null $data */
        $data = $this->service->get(null, null);
        $this->assertNull($data);
    }

    public function testgtopo30(): void
    {
        /** @var null $data */
        $data = $this->service->gtopo30(null, null);
        $this->assertNull($data);
    }

    public function testhierarchy(): void
    {
        /** @var null $data */
        $data = $this->service->hierarchy(null, null);
        $this->assertNull($data);
    }

    public function testnNeighbourhood(): void
    {
        /** @var null $data */
        $data = $this->service->nNeighbourhood(null, null);
        $this->assertNull($data);
    }

    public function testneighbours(): void
    {
        /** @var null $data */
        $data = $this->service->neighbours(null, null);
        $this->assertNull($data);
    }

    public function testocean(): void
    {
        /** @var null $data */
        $data = $this->service->ocean(null, null);
        $this->assertNull($data);
    }

    public function testpostalCodeCountryInfo(): void
    {
        /** @var null $data */
        $data = $this->service->postalCodeCountryInfo(null, null);
        $this->assertNull($data);
    }

    public function testpostalCodeLookup(): void
    {
        /** @var null $data */
        $data = $this->service->postalCodeLookup(null, null);
        $this->assertNull($data);
    }

    public function testpostalCodeSearch(): void
    {
        /** @var null $data */
        $data = $this->service->postalCodeSearch(null, null);
        $this->assertNull($data);
    }

    public function testrssToGeo(): void
    {
        /** @var null $data */
        $data = $this->service->rssToGeo(null, null);
        $this->assertNull($data);
    }

    public function testsearch(): void
    {
        /** @var null $data */
        $data = $this->service->search(null, null);
        $this->assertNull($data);
    }

    public function testsiblings(): void
    {
        /** @var null $data */
        $data = $this->service->siblings(null, null);
        $this->assertNull($data);
    }

    public function testsrtm1(): void
    {
        /** @var null $data */
        $data = $this->service->srtm1(null, null);
        $this->assertNull($data);
    }

    public function testsrtm3(): void
    {
        /** @var null $data */
        $data = $this->service->srtm3(null, null);
        $this->assertNull($data);
    }

    public function testtimezone(): void
    {
        /** @var null $data */
        $data = $this->service->timezone(null, null);
        $this->assertNull($data);
    }

    public function testweather(): void
    {
        /** @var null $data */
        $data = $this->service->weather(null, null);
        $this->assertNull($data);
    }

    public function testweatherIcao(): void
    {
        /** @var null $data */
        $data = $this->service->weatherIcao(null, null);
        $this->assertNull($data);
    }

    public function testwikipediaBoundingBox(): void
    {
        /** @var null $data */
        $data = $this->service->wikipediaBoundingBox(null, null);
        $this->assertNull($data);
    }

    public function testwikipediaSearch(): void
    {
        /** @var null $data */
        $data = $this->service->wikipediaSearch(null, null);
        $this->assertNull($data);
    }

    public function testsetTraitement(): void
    {
        /** @var null $data */
        $data = $this->service->setTraitement(null, null, null, null);
        $this->assertNull($data);
    }

    public function testsetUrl(): void
    {
        $url = $this->service->setUrl(null, null, null);
        $this->assertTrue(is_string($url));
    }
}
