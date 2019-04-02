<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
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
        $faker = Factory::create('fr_FR');
        $user  = new User();
        $user->setUsername('admin');
        $user->setPlainPassword('password');
        $user->setApiKey('api_admin');
        $user->setEmail('admin@email.fr');
        $user->addRole('ROLE_ADMIN');
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

        $superadmin = new User();
        $superadmin->setUsername('superadmin');
        $superadmin->setPlainPassword('password');
        $superadmin->setApiKey('api_superadmin');
        $superadmin->setEmail('superadmin@email.fr');
        $superadmin->addRole('ROLE_SUPER_ADMIN');
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

        $superadmin->setImageFile($file);
        $manager->persist($superadmin);

        $disabledUser = new User();
        $disabledUser->setUsername('disable');
        $disabledUser->setPlainPassword('disable');
        $disabledUser->setEmail('disable@email.fr');
        $disabledUser->setEnable(false);
        $disabledUser->addRole('ROLE_ADMIN');
        $manager->persist($disabledUser);

        $manager->flush();
    }
}
