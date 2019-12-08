<?php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Category;
use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Actor;
use App\Form\ProgramSearchType;
use App\Form\CategoryType;
use App\Service\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActorController extends AbstractController
{
    /**
     * Getting a program with a formatted slug for title
     *
     * @param string $slug The slugger
     * @Route("/actor/{slug}", defaults={"slug" = null}, name="show_actor")
     * @return Response
     */
    public function showByActor(Actor $actor, Slugify $slugify):Response
    {
        if (!$slugify) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }

        $actor->setSlug($slugify->generate($actor->getName()));


        $actor = $this->getDoctrine()
            ->getRepository(Actor::class)
            ->findOneBy(['name' => $actor->getName()]);

        $programs = $actor->getPrograms();

        return $this->render('wild/actor.html.twig', [
            'actor' => $actor,
            'programs' => $programs,
        ]);
    }
} 