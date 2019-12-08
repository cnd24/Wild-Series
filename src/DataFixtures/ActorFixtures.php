<?php

namespace App\DataFixtures;

use App\Entity\Program;
use App\Entity\Category;
use App\Entity\Actor;
use App\Service\Slugify;
use Faker;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{

    const ACTORS = [
        'Andrew Lincoln',
        'Norman Reedus',
        'Danai Gurira',
        'Victoria Pedretti',
    ];



    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $slugify = new Slugify();

        for ($i=0; $i<10; $i++) {
                $actor = new Actor();
                $actor->setName($faker->name);
                $actor->setSlug($slugify->generate($actor->getName()));
                $manager->persist($actor);
                $actor->addProgram($this->getReference('program_' . rand(0, 3)));
        }

        foreach (self::ACTORS as $key => $actorName) {
            $actor = new Actor();
            $actor->setName($actorName);
            $actor->setSlug($slugify->generate($actor->getName()));
            $manager->persist($actor);
            $actor->addProgram($this->getReference('program_' . rand(0, 3)));
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}
