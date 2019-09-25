<?php

namespace Labstag\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Labstag\Entity\Configuration;
use Labstag\Service\OauthService;

class ConfigurationFixtures extends Fixture
{

    /**
     * @var OauthService
     */
    private $oauthService;

    public function __construct(OauthService $oauthService)
    {
        $this->oauthService = $oauthService;
    }

    public function load(ObjectManager $manager)
    {
        $this->add($manager);
    }

    private function add(ObjectManager $manager)
    {
        $viewport = 'width=device-width, initial-scale=1, shrink-to-fit=no';
        $data     = [
            'languagedefault' => 'fr',
            'language'        => [
                'en',
                'fr',
            ],
            'site_email'      => 'letoullec.martial@gmail.com',
            'site_no-reply'   => 'no-reply@labstag.fr',
            'site_title'      => 'labstag',
            'site_copyright'  => 'Copyright '.date('Y'),
            'geonames_id'     => [],
            'geonames_count'  => [],
            'oauth'           => [],
            'meta'            => [
                [
                    'viewport'    => $viewport,
                    'author'      => 'koromerzhin',
                    'theme-color' => '#ff0000',
                    'description' => '',
                    'keywords'    => '',
                ],
            ],
            'disclaimer'      => [
                [
                    'activate'     => 0,
                    'message'      => '',
                    'title'        => '',
                    'url-redirect' => 'http://www.google.fr',
                ],
            ],
            'moment'          => [
                [
                    'format' => 'MMMM Do YYYY, H:mm:ss',
                    'lang'   => 'fr',
                ],
            ],
            'wysiwyg'         => [
                ['lang' => 'fr_FR'],
            ],
            'datatable'       => [
                [
                    'lang'     => 'fr-FR',
                    'pagelist' => '[5, 10, 25, 50, All]',
                ],
            ],
        ];

        $names = explode(',', getenv('SYMFONY_DOTENV_VARS'));
        $env   = [];
        foreach ($names as $name) {
            $env[$name] = getenv($name);
        }

        ksort($env);
        if (array_key_exists('GEONAMES_ID', $env)) {
            $data['geonames_id'] = explode(',', $env['GEONAMES_ID']);
        }

        $oauth = [];
        foreach ($env as $key => $val) {
            if (0 != substr_count($key, 'OAUTH_')) {
                $code = str_replace('OAUTH_', '', $key);
                $code = strtolower($code);
                [
                    $type,
                    $key,
                ]     = explode('_', $code);
                if (!isset($oauth[$type])) {
                    $oauth[$type] = [
                        'activate' => $this->oauthService->getActivedProvider($type),
                        'type'     => $type,
                    ];
                }

                $oauth[$type][$key] = $val;
            }
        }

        foreach ($oauth as $row) {
            array_push($data['oauth'], $row);
        }

        foreach ($data as $key => $value) {
            $configuration = new Configuration();
            $configuration->setName($key);
            $configuration->setValue($value);
            $manager->persist($configuration);
        }

        $manager->flush();
    }
}
