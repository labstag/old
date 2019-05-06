<?php

namespace Labstag\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ConfigurationFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = [
            [
                'name'  => 'site_title',
                'value' => 'labstag',
            ],
        ];
        foreach ($data as $row) {
            $configuration = new Configuration();
            $configuration->setName($row['name']);
            $configuration->setValue($row['value']);
            $manager->persist($configuration);
        }

        $manager->flush();
    }
}
