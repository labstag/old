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
        $encodePassword = $this->passwordEncoder->encodePassword(
            $user,
            "password"
        );
        $user->setApiKey('test_api_key');
        $user->addRole('ROLE_ADMIN');
        $user->setPassword($encodePassword);
        $manager->persist($user);

        
        $disabledUser = new User();
        $disabledUser->setUsername('disable');
        $encodePassword = $this->passwordEncoder->encodePassword(
            $disabledUser,
            "disable"
        );
        $disabledUser->setEnable(false);
        $disabledUser->addRole('ROLE_ADMIN');
        $disabledUser->setPassword($encodePassword);
        $manager->persist($disabledUser);

        $manager->flush();
    }
}
