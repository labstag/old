<?php

namespace Labstag\Service;

class GeonameService
{
    public function astergdem(?string $format, ?array $params = [])
    {
        $path    = 'astergdem';
        $aformat = [
            'xml',
            'json',
            'txt',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function children(?string $format, ?array $params = [])
    {
        $path    = 'children';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function countains(?string $format, ?array $params = [])
    {
        $path    = 'countains';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function countryCode(?string $format, ?array $params = [])
    {
        $path    = 'countryCode';
        $aformat = [
            'xml',
            'json',
            'txt',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function countryInfo(?string $format, ?array $params = [])
    {
        $path    = 'countryInfo';
        $aformat = [
            'xml',
            'json',
            'csv',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function countrySubdivision(?string $format, ?array $params = [])
    {
        $path    = 'countrySubdivision';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function earthquakes(?string $format, ?array $params = [])
    {
        $path    = 'earthquakes';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function extentedFindNearby(?string $format, ?array $params = [])
    {
        $path    = 'extendedFindNearby';
        $aformat = ['xml'];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function findNearby(?string $format, ?array $params = [])
    {
        $path    = 'findNearby';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function findNearbyPlaceName(?string $format, ?array $params = [])
    {
        $path    = 'findNearbyPlaceName';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function findNearbyPostalCodes(?string $format, ?array $params = [])
    {
        $path    = 'findNearbyPostalCodes';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function findNearbyStreets(?string $format, ?array $params = [])
    {
        $path    = 'findNearbyStreets';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function findNearbyStreetsoSM(?string $format, ?array $params = [])
    {
        $path    = 'findNearbyStreetsOSM';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function findNearByWeather(?string $format, ?array $params = [])
    {
        $path    = 'findNearByWeather';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function findNearbyWikipedia(?string $format, ?array $params = [])
    {
        $path    = 'findNearbyWikipedia';
        $aformat = [
            'xml',
            'json',
            'rss',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function findNearestAddress(?string $format, ?array $params = [])
    {
        $path    = 'findNearestAddress';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function findNearestIntersection(?string $format, ?array $params = [])
    {
        $path    = 'findNearestIntersection';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function findNearbyPOIsOSM(?string $format, ?array $params = [])
    {
        $path    = 'findNearbyPOIsOSM';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function address(?string $format, ?array $params = [])
    {
        $path    = 'address';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function geoCodeAddress(?string $format, ?array $params = [])
    {
        $path    = 'geoCodeAddress';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function getStreetNameLookup(?string $format, ?array $params = [])
    {
        $path    = 'streetNameLookup';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function get(?string $format, ?array $params = [])
    {
        $path    = 'get';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function gtopo30(?string $format, ?array $params = [])
    {
        $path    = 'gtopo30';
        $aformat = [
            'xml',
            'json',
            'txt',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function hierarchy(?string $format, ?array $params = [])
    {
        $path    = 'hierarchy';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function nNeighbourhood(?string $format, ?array $params = [])
    {
        $path    = 'neighbourhood';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function neighbours(?string $format, ?array $params = [])
    {
        $path    = 'neighbours';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function ocean(?string $format, ?array $params = [])
    {
        $path    = 'ocean';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function postalCodeCountryInfo(?string $format, ?array $params = [])
    {
        $path    = 'postalCodeCountryInfo';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function postalCodeLookup(?string $format, ?array $params = [])
    {
        $path    = 'postalCodeLookup';
        $aformat = ['json'];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function postalCodeSearch(?string $format, ?array $params = [])
    {
        $path    = 'postalCodeSearch';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function rssToGeo(?string $format, ?array $params = [])
    {
        $path    = 'rssToGeo';
        $aformat = [
            'rss',
            'kml',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function search(?string $format, ?array $params = [])
    {
        $path    = 'search';
        $aformat = [
            'xml',
            'json',
            'rdf',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function siblings(?string $format, ?array $params = [])
    {
        $path    = 'siblings';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function srtm1(?string $format, ?array $params = [])
    {
        $path    = 'srtm1';
        $aformat = [
            'xml',
            'json',
            'txt',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function srtm3(?string $format, ?array $params = [])
    {
        $path    = 'srtm3';
        $aformat = [
            'xml',
            'json',
            'txt',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function timezone(?string $format, ?array $params = [])
    {
        $path    = 'timezone';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function weather(?string $format, ?array $params = [])
    {
        $path    = 'weather';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function weatherIcao(?string $format, ?array $params = [])
    {
        $path    = 'weatherIcao';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function wikipediaBoundingBox(?string $format, ?array $params = [])
    {
        $path    = 'wikipediaBoundingBox';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function wikipediaSearch(?string $format, ?array $params = [])
    {
        $path    = 'wikipediaSearch';
        $aformat = [
            'xml',
            'json',
        ];

        return $this->setTraitement($path, $aformat, $format, $params);
    }

    public function setTraitement(
        ?string $path,
        ?array $aformat,
        ?string $format,
        ?array $params = []
    )
    {
        if (is_null($format)) {
            return null;
        }

        if (is_null($aformat)) {
            return null;
        }

        if (is_null($format)) {
            return null;
        }

        if (!in_array($format, $aformat)) {
            return null;
        }

        $url     = $this->setUrl($path, $format, $params);
        $content = $this->getContents($url);

        return $this->traitmentContents($content, $format);
    }

    public function getContents(?string $url): string
    {
        if (is_null($url)) {
            return '';
        }

        return file_get_contents($url);
    }

    public function traitmentContents(?string $content, ?string $format): array
    {
        if (is_null($content)) {
            return [];
        }

        switch ($format) {
            case 'json':
                $data = json_decode($content, true);

                break;
            case 'rss':
            case 'xml':
                $xml  = simplexml_load_string(
                    $content,
                    'SimpleXMLElement',
                    LIBXML_NOCDATA
                );
                $json = json_encode($xml);
                $data = json_decode($json, true);

                break;
            default:
                $data = [];

                break;
        }

        return $data;
    }

    public function setUrl(?string $path, ?string $format, ?array $params = [])
    {
        $params = is_null($params) ? [] : $params;

        if (is_null($path) || is_null($format)) {
            return '';
        }

        $url = 'http://api.geonames.org/'.$path;
        $this->setParamUrl($format, $url);
        ksort($params);
        $query = http_build_query($params);

        return ('' != $query) ? $url.'?'.$query : $url;
    }

    private function setParamUrl($format, &$url)
    {
        switch ($format) {
            case 'json':
                $url .= 'JSON';

                break;
            case 'csv':
                $url .= 'CSV';

                break;
            case 'rss':
                $url .= 'RSS';

                break;
            case 'xml':
                $params['type'] = 'xml';

                break;
            case 'kml':
                $params['type'] = 'kml';

                break;
            default:
                break;
        }
    }
}
