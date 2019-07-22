<?php

namespace Labstag\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Labstag\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * password Encoder.
     *
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->add($manager);
    }

    public function getDependencies()
    {
        return [
            FilesFixtures::class,
        ];
    }

    private function add(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $users = [
            [
                'username' => 'admin',
                'password' => 'password',
                'apikey'   => 'api_admin',
                'email'    => 'admin@email.fr',
                'role'     => 'ROLE_ADMIN',
            ],
            [
                'username' => 'superadmin',
                'password' => 'password',
                'apikey'   => 'api_superadmin',
                'email'    => 'superadmin@email.fr',
                'role'     => 'ROLE_SUPER_ADMIN',
            ],
            [
                'username' => 'disable',
                'password' => 'disable',
                'email'    => 'disable@email.fr',
                'role'     => 'ROLE_ADMIN',
            ]
        ];
        foreach ($users as $dataUser) {
            $user  = new User();
            $user->setUsername($dataUser['username']);
            $user->setPlainPassword($dataUser['username']);
            if (!isset(dataUser['apikey'])) {
                $user->setApiKey($dataUser['apikey']);
            }
            $user->setEmail($dataUser['email']);
            $user->addRole($dataUser['role']);
            $image   = $faker->unique()->imageUrl(200, 200);
            $content = file_get_contents($image);
            $tmpfile = tmpfile();
            $data    = stream_get_meta_data($tmpfile);
            file_put_contents($data['uri'], $content);
            $file = new UploadedFile(
                $data['uri'],
                'image.jpg',
                filesize($data['uri']),
                null,
                true
            );
    
            $user->setImageFile($file);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
