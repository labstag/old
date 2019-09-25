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
        $this->service = self::$container->get(GeonameService::class);
    }

    public function testastergdem()
    {
        $data = $this->service->astergdem(null, null);
        $this->assertNull($data);
    }

    public function testchildren()
    {
        $data = $this->service->children(null, null);
        $this->assertNull($data);
    }

    public function testcountains()
    {
        $data = $this->service->countains(null, null);
        $this->assertNull($data);
    }

    public function testcountryCode()
    {
        $data = $this->service->countryCode(null, null);
        $this->assertNull($data);
    }

    public function testcountryInfo()
    {
        $data = $this->service->countryInfo(null, null);
        $this->assertNull($data);
    }

    public function testcountrySubdivision()
    {
        $data = $this->service->countrySubdivision(null, null);
        $this->assertNull($data);
    }

    public function testearthquakes()
    {
        $data = $this->service->earthquakes(null, null);
        $this->assertNull($data);
    }

    public function testextentedFindNearby()
    {
        $data = $this->service->extentedFindNearby(null, null);
        $this->assertNull($data);
    }

    public function testfindNearby()
    {
        $data = $this->service->findNearby(null, null);
        $this->assertNull($data);
    }

    public function testfindNearbyPlaceName()
    {
        $data = $this->service->findNearbyPlaceName(null, null);
        $this->assertNull($data);
    }

    public function testfindNearbyPostalCodes()
    {
        $data = $this->service->findNearbyPostalCodes(null, null);
        $this->assertNull($data);
    }

    public function testfindNearbyStreets()
    {
        $data = $this->service->findNearbyStreets(null, null);
        $this->assertNull($data);
    }

    public function testfindNearbyStreetsoSM()
    {
        $data = $this->service->findNearbyStreetsoSM(null, null);
        $this->assertNull($data);
    }

    public function testfindNearByWeather()
    {
        $data = $this->service->findNearByWeather(null, null);
        $this->assertNull($data);
    }

    public function testfindNearbyWikipedia()
    {
        $data = $this->service->findNearbyWikipedia(null, null);
        $this->assertNull($data);
    }

    public function testfindNearestAddress()
    {
        $data = $this->service->findNearestAddress(null, null);
        $this->assertNull($data);
    }

    public function testfindNearestIntersection()
    {
        $data = $this->service->findNearestIntersection(null, null);
        $this->assertNull($data);
    }

    public function testfindNearbyPOIsOSM()
    {
        $data = $this->service->findNearbyPOIsOSM(null, null);
        $this->assertNull($data);
    }

    public function testaddress()
    {
        $data = $this->service->address(null, null);
        $this->assertNull($data);
    }

    public function testgeoCodeAddress()
    {
        $data = $this->service->geoCodeAddress(null, null);
        $this->assertNull($data);
    }

    public function testgetStreetNameLookup()
    {
        $data = $this->service->getStreetNameLookup(null, null);
        $this->assertNull($data);
    }

    public function testget()
    {
        $data = $this->service->get(null, null);
        $this->assertNull($data);
    }

    public function testgtopo30()
    {
        $data = $this->service->gtopo30(null, null);
        $this->assertNull($data);
    }

    public function testhierarchy()
    {
        $data = $this->service->hierarchy(null, null);
        $this->assertNull($data);
    }

    public function testnNeighbourhood()
    {
        $data = $this->service->nNeighbourhood(null, null);
        $this->assertNull($data);
    }

    public function testneighbours()
    {
        $data = $this->service->neighbours(null, null);
        $this->assertNull($data);
    }

    public function testocean()
    {
        $data = $this->service->ocean(null, null);
        $this->assertNull($data);
    }

    public function testpostalCodeCountryInfo()
    {
        $data = $this->service->postalCodeCountryInfo(null, null);
        $this->assertNull($data);
    }

    public function testpostalCodeLookup()
    {
        $data = $this->service->postalCodeLookup(null, null);
        $this->assertNull($data);
    }

    public function testpostalCodeSearch()
    {
        $data = $this->service->postalCodeSearch(null, null);
        $this->assertNull($data);
    }

    public function testrssToGeo()
    {
        $data = $this->service->rssToGeo(null, null);
        $this->assertNull($data);
    }

    public function testsearch()
    {
        $data = $this->service->search(null, null);
        $this->assertNull($data);
    }

    public function testsiblings()
    {
        $data = $this->service->siblings(null, null);
        $this->assertNull($data);
    }

    public function testsrtm1()
    {
        $data = $this->service->srtm1(null, null);
        $this->assertNull($data);
    }

    public function testsrtm3()
    {
        $data = $this->service->srtm3(null, null);
        $this->assertNull($data);
    }

    public function testtimezone()
    {
        $data = $this->service->timezone(null, null);
        $this->assertNull($data);
    }

    public function testweather()
    {
        $data = $this->service->weather(null, null);
        $this->assertNull($data);
    }

    public function testweatherIcao()
    {
        $data = $this->service->weatherIcao(null, null);
        $this->assertNull($data);
    }

    public function testwikipediaBoundingBox()
    {
        $data = $this->service->wikipediaBoundingBox(null, null);
        $this->assertNull($data);
    }

    public function testwikipediaSearch()
    {
        $data = $this->service->wikipediaSearch(null, null);
        $this->assertNull($data);
    }

    public function testsetTraitement()
    {
        $data = $this->service->setTraitement(null, null, null, null);
        $this->assertNull($data);
    }

    public function testsetUrl()
    {
        $url = $this->service->setUrl(null, null, null);
        $this->assertTrue(is_string($url));
    }
}
