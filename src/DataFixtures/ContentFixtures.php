<?php

namespace App\DataFixtures;

use App\Entity\Content;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

require_once 'vendor/autoload.php';

class ContentFixtures extends Fixture implements DependentFixtureInterface
{

    const MAX_CONTENT = 12;

    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create();

        for ($i = 0; $i < ContentFixtures::MAX_CONTENT; $i++) {
            $content = new Content();

            $content->setAuthor($this->getReference('user_' . random_int(0, UserFixtures::MAX_USER-1)));
            $content->setPublisher($this->getReference('user_' . random_int(0, 6)));
            $content->setCategory($this->getReference('category_' . random_int(0, CategoryFixtures::MAX_CATEGORIES-1)));
            $content->setStatus(random_int(0, 7));
            $content->setText($faker->text(255));
            $content->setPublicationDate(new \DateTime());
            $manager->persist($content);

            $manager->flush();
        }

    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return class-string[]
     */
    public function getDependencies()
    {
        return [UserFixtures::class, CategoryFixtures::class];
    }
}