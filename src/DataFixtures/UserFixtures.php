<?php

namespace Labstag\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Labstag\Entity\Email;
use Labstag\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
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
                'email'    => ['admin@email.fr'],
                'role'     => 'ROLE_ADMIN',
            ],
            [
                'username' => 'superadmin',
                'password' => 'password',
                'apikey'   => 'api_superadmin',
                'email'    => ['superadmin@email.fr'],
                'role'     => 'ROLE_SUPER_ADMIN',
            ],
            [
                'username' => 'disable',
                'password' => 'disable',
                'email'    => ['disable@email.fr'],
                'role'     => 'ROLE_ADMIN',
            ],
        ];
        foreach ($users as $dataUser) {
            $user = new User();
            $user->setUsername($dataUser['username']);
            $user->setPlainPassword($dataUser['password']);
            if (isset($dataUser['apikey'])) {
                $user->setApiKey($dataUser['apikey']);
            }

            foreach ($dataUser['email'] as $index => $adresse) {
                $email = new Email();
                $email->setAdresse($adresse);
                $principal = (0 == $index) ? true : false;
                $email->setPrincipal($principal);
                $email->setRefuser($user);
                $manager->persist($email);
            }

            $user->setEmail($dataUser['email'][0]);
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
