<?php

namespace App\DataFixtures;

use App\Entity\Content;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ContentFixtures extends Fixture implements DependentFixtureInterface
{

    const MAX_CONTENT = 20;

    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create();

        for ($i = 0; $i < ContentFixtures::MAX_CONTENT; $i++) {
            $content = new Content();

            $content->setAuthor($this->getReference('user_' . random_int(0, UserFixtures::MAX_USER-1)));
            if ($i > 10) {
                $content->setPublisher($this->getReference('user_' . random_int(0, 6)));
                $content->setPublicationDate(new \DateTime());
                $content->setStatus(1);
            }
            else{
                $content->setStatus(0);
            }
            $content->setTitle($faker->sentence);
            $content->setCategory($this->getReference('category_' . random_int(0, CategoryFixtures::MAX_CATEGORIES-1)));
            $content->setText($faker->text(255));
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