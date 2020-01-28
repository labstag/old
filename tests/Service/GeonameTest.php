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
        $data = $this->service->astergdem(null, null);
        $this->assertNull($data);
    }

    public function testchildren(): void
    {
        $data = $this->service->children(null, null);
        $this->assertNull($data);
    }

    public function testcountains(): void
    {
        $data = $this->service->countains(null, null);
        $this->assertNull($data);
    }

    public function testcountryCode(): void
    {
        $data = $this->service->countryCode(null, null);
        $this->assertNull($data);
    }

    public function testcountryInfo(): void
    {
        $data = $this->service->countryInfo(null, null);
        $this->assertNull($data);
    }

    public function testcountrySubdivision(): void
    {
        $data = $this->service->countrySubdivision(null, null);
        $this->assertNull($data);
    }

    public function testearthquakes(): void
    {
        $data = $this->service->earthquakes(null, null);
        $this->assertNull($data);
    }

    public function testextentedFindNearby(): void
    {
        $data = $this->service->extentedFindNearby(null, null);
        $this->assertNull($data);
    }

    public function testfindNearby(): void
    {
        $data = $this->service->findNearby(null, null);
        $this->assertNull($data);
    }

    public function testfindNearbyPlaceName(): void
    {
        $data = $this->service->findNearbyPlaceName(null, null);
        $this->assertNull($data);
    }

    public function testfindNearbyPostalCodes(): void
    {
        $data = $this->service->findNearbyPostalCodes(null, null);
        $this->assertNull($data);
    }

    public function testfindNearbyStreets(): void
    {
        $data = $this->service->findNearbyStreets(null, null);
        $this->assertNull($data);
    }

    public function testfindNearbyStreetsoSM(): void
    {
        $data = $this->service->findNearbyStreetsoSM(null, null);
        $this->assertNull($data);
    }

    public function testfindNearByWeather(): void
    {
        $data = $this->service->findNearByWeather(null, null);
        $this->assertNull($data);
    }

    public function testfindNearbyWikipedia(): void
    {
        $data = $this->service->findNearbyWikipedia(null, null);
        $this->assertNull($data);
    }

    public function testfindNearestAddress(): void
    {
        $data = $this->service->findNearestAddress(null, null);
        $this->assertNull($data);
    }

    public function testfindNearestIntersection(): void
    {
        $data = $this->service->findNearestIntersection(null, null);
        $this->assertNull($data);
    }

    public function testfindNearbyPOIsOSM(): void
    {
        $data = $this->service->findNearbyPOIsOSM(null, null);
        $this->assertNull($data);
    }

    public function testaddress(): void
    {
        $data = $this->service->address(null, null);
        $this->assertNull($data);
    }

    public function testgeoCodeAddress(): void
    {
        $data = $this->service->geoCodeAddress(null, null);
        $this->assertNull($data);
    }

    public function testgetStreetNameLookup(): void
    {
        $data = $this->service->getStreetNameLookup(null, null);
        $this->assertNull($data);
    }

    public function testget(): void
    {
        $data = $this->service->get(null, null);
        $this->assertNull($data);
    }

    public function testgtopo30(): void
    {
        $data = $this->service->gtopo30(null, null);
        $this->assertNull($data);
    }

    public function testhierarchy(): void
    {
        $data = $this->service->hierarchy(null, null);
        $this->assertNull($data);
    }

    public function testnNeighbourhood(): void
    {
        $data = $this->service->nNeighbourhood(null, null);
        $this->assertNull($data);
    }

    public function testneighbours(): void
    {
        $data = $this->service->neighbours(null, null);
        $this->assertNull($data);
    }

    public function testocean(): void
    {
        $data = $this->service->ocean(null, null);
        $this->assertNull($data);
    }

    public function testpostalCodeCountryInfo(): void
    {
        $data = $this->service->postalCodeCountryInfo(null, null);
        $this->assertNull($data);
    }

    public function testpostalCodeLookup(): void
    {
        $data = $this->service->postalCodeLookup(null, null);
        $this->assertNull($data);
    }

    public function testpostalCodeSearch(): void
    {
        $data = $this->service->postalCodeSearch(null, null);
        $this->assertNull($data);
    }

    public function testrssToGeo(): void
    {
        $data = $this->service->rssToGeo(null, null);
        $this->assertNull($data);
    }

    public function testsearch(): void
    {
        $data = $this->service->search(null, null);
        $this->assertNull($data);
    }

    public function testsiblings(): void
    {
        $data = $this->service->siblings(null, null);
        $this->assertNull($data);
    }

    public function testsrtm1(): void
    {
        $data = $this->service->srtm1(null, null);
        $this->assertNull($data);
    }

    public function testsrtm3(): void
    {
        $data = $this->service->srtm3(null, null);
        $this->assertNull($data);
    }

    public function testtimezone(): void
    {
        $data = $this->service->timezone(null, null);
        $this->assertNull($data);
    }

    public function testweather(): void
    {
        $data = $this->service->weather(null, null);
        $this->assertNull($data);
    }

    public function testweatherIcao(): void
    {
        $data = $this->service->weatherIcao(null, null);
        $this->assertNull($data);
    }

    public function testwikipediaBoundingBox(): void
    {
        $data = $this->service->wikipediaBoundingBox(null, null);
        $this->assertNull($data);
    }

    public function testwikipediaSearch(): void
    {
        $data = $this->service->wikipediaSearch(null, null);
        $this->assertNull($data);
    }

    public function testsetTraitement(): void
    {
        $data = $this->service->setTraitement(null, null, null, null);
        $this->assertNull($data);
    }

    public function testsetUrl(): void
    {
        $url = $this->service->setUrl(null, null, null);
        $this->assertTrue(is_string($url));
    }
}
