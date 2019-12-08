<?php

namespace App\DataFixtures;

use App\Entity\Program;
use App\Entity\Category;
use App\Entity\Actor;
use App\Entity\Season;
use App\Entity\Episode;
use App\Service\Slugify;
use Faker;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $slugify = new Slugify();

        for ($i=0; $i < 40; $i++){
            $episode = new Episode();
            $episode->setNumber($faker->numberBetween(1,11));
            $episode->setTitle($faker->words(3, true));
            $episode->setSynopsis($faker->paragraph);
            $episode->setSeason($this->getReference('season'.rand(0,10)));
            $episode->setSlug($slugify->generate($episode->getTitle()));
            $manager->persist($episode);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }
}
