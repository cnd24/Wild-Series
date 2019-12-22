<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use App\Entity\Category;
use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
       $faker = \Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 1000; $i++) {
            $category = new Category();
            $category->setName($faker->word);
            $manager->persist($category);
            $this->addReference("category_".$i, $category);

            $program = new Program();
            $program->setTitle($faker->word);
            $program->setSummary($faker->text(100));
            $program->setSlug($faker->word);
            $program->setCategory($this->getReference("category_".$i));
            $this->addReference("program_".$i, $program);
            $manager->persist($program);

            for($j = 1; $j <= 5; $j ++) {
                $actor = new Actor();
                $actor->setName($faker->name);
                $actor->setSlug($faker->word);
                $actor->addProgram($this->getReference("program_".$i));
                $manager->persist($actor);
            }

        }
        $manager->flush();

    }
}
