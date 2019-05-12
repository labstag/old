<?php

namespace Labstag\DataFixtures;

use Labstag\Entity\Configuration;
use Labstag\Services\OauthServices;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ConfigurationFixtures extends Fixture
{

    public function __construct(ContainerInterface $container)
    {
        $this->container     = $container;
        $this->oauthServices = $this->container->get(OauthServices::class);
    }

    public function load(ObjectManager $manager)
    {
        $data  = [
            'site_title' => 'labstag',
            'oauth'      => [],
            'meta'       => [
                [
                    'viewport'    => 'width=device-width, initial-scale=1, shrink-to-fit=no',
                    'author'      => 'koromerzhin',
                    'theme-color' => '#ff0000',
                    'description' => '',
                    'keywords'    => '',
                ]
            ],
            'disclaimer' => [
                [
                    'activate'     => 0,
                    'message'      => '',
                    'title'        => '',
                    'url-redirect' => 'http://www.google.fr',
                ]
            ],
            'moment'     => [
                [
                    'format' => 'MMMM Do YYYY, H:mm:ss',
                    'lang'   => 'fr',
                ]
            ],
            'wysiwyg'    => [
                [
                    'lang' => 'fr_FR'
                ]
            ],
            'datatable'  => [
                [
                    'lang'     => 'fr-FR',
                    'pagelist' => '[5, 10, 25, 50, All]'
                ]
            ]
        ];
        $param = $_SERVER;
        $oauth = [];
        foreach ($param as $key => $val) {
            if (0 != substr_count($key, 'OAUTH_')) {
                $code = str_replace('OAUTH_', '', $key);
                $code = strtolower($code);
                [
                    $type,
                    $key,
                ]     = explode('_', $code);
                if (!isset($oauth[$type])) {
                    $oauth[$type] = [
                        'activate' => $this->oauthServices->getActivedProvider($type),
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
