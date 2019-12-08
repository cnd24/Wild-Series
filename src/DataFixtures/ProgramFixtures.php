<?php

namespace App\DataFixtures;

use App\Service\Slugify;
use Faker;
use App\Entity\Program;
use App\Entity\Category;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{

    const PROGRAMS = [

        'Walking Dead' => [
            'summary' => 'Le policier Rick Grimes se réveille après un long coma. Il découvre avec effarement que le monde, ravagé par une épidémie, est envahi par les morts-vivants.',
            'poster' => 'https://images-na.ssl-images-amazon.com/images/I/51U%2B5eFWjQL.jpg',
            'category' => 'categorie_4',
        ],
        'The Haunting Of Hill House' => [
            'summary' => 'Plusieurs frères et sœurs qui, enfants, ont grandi dans la demeure qui allait devenir la maison hantée la plus célèbre des États-Unis, sont contraints de se réunir pour finalement affronter les fantômes de leur passé.',
            'poster' => 'http://fr.web.img4.acsta.net/f_png/c_216_288/o_logo-netflix-n.png_5_se/pictures/18/09/19/18/46/2766026.jpg',
            'category' => 'categorie_4',
        ],
        'American Horror Story' => [
            'summary' => 'A chaque saison, son histoire. American Horror Story nous embarque dans des récits à la fois poignants et cauchemardesques, mêlant la peur, le gore et le politiquement correct.',
            'poster' => 'http://fr.web.img4.acsta.net/pictures/14/09/23/11/45/584667.jpg',
            'category' => 'categorie_4',
        ],
        'Love Death And Robots' => [
            'summary' => 'Un yaourt susceptible, des soldats lycanthropes, des robots déchaînés, des monstres-poubelles, des chasseurs de primes cyborgs, des araignées extraterrestres et des démons assoiffés de sang : tout ce beau monde est réuni dans 18 courts métrages animés déconseillés aux âmes sensibles.',
            'poster' => 'https://media.senscritique.com/media/000018430334/source_big/Love_Death_Robots.jpg',
            'category' => 'categorie_4',
        ],
        'Penny Dreadful' => [
            'summary' => 'Dans le Londres ancien, Vanessa Ives, une jeune femme puissante aux pouvoirs hypnotiques, allie ses forces à celles de Ethan, un garçon rebelle et violent aux allures de cowboy, et de Sir Malcolm, un vieil homme riche aux ressources inépuisables. Ensemble, ils combattent un ennemi inconnu, presque invisible, qui ne semble pas humain et qui massacre la population.',
            'poster' => 'https://www.ecranlarge.com/uploads/image/001/150/3ogg3o65cf16bhmrh2nioiif9ad-405.jpg',
            'category' => 'categorie_4',
        ],
        'Fear The Walking Dead' => [
            'summary' => 'La série se déroule au tout début de l épidémie relatée dans la série mère The Walking Dead et se passe dans la ville de Los Angeles, et non à Atlanta. Madison est conseillère dans un lycée de Los Angeles. Depuis la mort de son mari, elle élève seule ses deux enfants : Alicia, excellente élève qui découvre les premiers émois amoureux, et son grand frère Nick qui a quitté la fac et a sombré dans la drogue.',
            'poster' => 'http://fr.web.img6.acsta.net/pictures/19/07/19/09/25/2947268.jpg',
            'category' => 'categorie_4',
        ],
        'Les Simpson' => [
            'summary' => 'Les aventures des Simpson à Springfield. Homer travaille dans une centrale nucléaire et Marge s\'occupe de sa progéniture : Bart, Lisa et Maggie.',
            'poster' => 'https://media.senscritique.com/media/000006490512/160/Les_Simpson.jpg',
            'category' => 'categorie_2',
        ],
        'Les aventures de Tintin' => [
            'summary' => 'La série reprend les aventures du célèbre reporter belge et de son fidèle compagnon Milou, tirées des albums d\'Hergé.',
            'poster' => 'https://media.senscritique.com/media/000010021319/160/Les_aventures_de_Tintin.png',
            'category' => 'categorie_2',
        ],
    ];

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $slugify = new Slugify();

        $i=0;
        foreach (self::PROGRAMS as $title => $data){
            $program = new Program();
            $program->setTitle($title);
            $program->setSummary($faker->paragraph);
            $program->setPoster($data['poster']);
            $program->setSlug($slugify->generate($program->getTitle()));
            $manager->persist($program);
            $this->addReference('program_' . $i, $program);
            $program->setCategory($this->getReference($data['category']));
            $i++;
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }
}
