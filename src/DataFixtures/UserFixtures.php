<?php

namespace Labstag\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use bheller\ImagesGenerator\ImagesGeneratorProvider;
use Labstag\Entity\Email;
use Labstag\Entity\Phone;
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
        $faker->addProvider(new ImagesGeneratorProvider($faker));
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
                $email->setRefuser($user);
                $email->setAdresse($adresse);
                $principal = (0 == $index) ? true : false;
                $email->setPrincipal($principal);
                $email->setChecked(true);
                $manager->persist($email);
            }

            $phones = rand(0, 2);
            for ($index = 1; $index <= $phones; ++$index) {
                $number = $faker->unique()->e164PhoneNumber();
                $phone  = new Phone();
                $phone->setRefuser($user);
                $phone->setNumero($number);
                $phone->setChecked(true);
                $phone->setType($faker->unique()->word());
                $manager->persist($phone);
            }

            $user->setEmail($dataUser['email'][0]);
            $user->addRole($dataUser['role']);

            try {
                $image = $faker->imageGEnerator(null, 1920, 1920);
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
            } catch (Exception $exception) {
            }

            $manager->persist($user);
        }

        $manager->flush();
    }
}
