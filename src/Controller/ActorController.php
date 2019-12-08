<?php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Category;
use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\Actor;
use App\Form\ProgramSearchType;
use App\Form\CategoryType;
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
    public function showByActor(?string $slug):Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $actor = $this->getDoctrine()
            ->getRepository(Actor::class)
            ->findOneBy(['name' => mb_strtolower($slug)]);

        $programs = $actor->getPrograms();

        if (!$actor) {
            throw $this->createNotFoundException(
                'No actor with '.$slug.' name, found in actor\'s table.'
            );
        }

        return $this->render('wild/actor.html.twig', [
            'actor' => $actor,
            'slug'  => $slug,
            'programs' => $programs,
        ]);
    }
} 