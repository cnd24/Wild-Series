<?php

namespace App\DataFixtures;

use App\Entity\Program;
use App\Entity\Category;
use App\Entity\Actor;
use App\Entity\Season;
use Faker;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i=0; $i < 40; $i++){
            $season = new Season();
            $season->setYear($faker->year);
            $season->setDescription('Saison ' . rand(1,11));
            $season->setProgram($this->getReference('program_'.rand(0,7)));
            $this->addReference('season'.$i, $season);
            $manager->persist($season);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}
