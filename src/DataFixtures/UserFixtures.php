<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    /**
     * password Encoder
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
        $user = new User();
        $user->setUsername('admin');
        $user->setPlainPassword('password');
        $user->setApiKey('test_api_key');
        $user->setEmail('admin@email.fr');
        $user->addRole('ROLE_SUPER_ADMIN');
        $manager->persist($user);

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
