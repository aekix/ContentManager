<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    const MAX_USER = 20;
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create('fr_FR');

        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'root'));
        $user->setEnabled(1);
        $user->setMail($faker->email);
        $user->setFirstname($faker->firstName);
        $user->setLastname($faker->lastName);
        $manager->persist($user);
        $this->addReference('user_' . 0, $user);

        for ($i = 1; $i < 3; $i++) {
            $user = new User();
            $user->setRoles(['ROLE_COMM']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'root'));
            $user->setEnabled(1);
            $user->setMail($faker->email);
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }

        for ($i = 3; $i < 6; $i++) {
            $user = new User();
            $user->setRoles(['ROLE_REVIEWER']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'root'));
            $user->setEnabled(1);
            $user->setMail($faker->email);
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }

        for ($i = 6; $i < UserFixtures::MAX_USER; $i++) {
            $user = new User();
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'root'));
            $user->setEnabled(random_int(0, 1));
            $user->setMail($faker->email);
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $manager->persist($user);
            $this->addReference('user_' . $i, $user);

            $manager->flush();
        }

    }
}