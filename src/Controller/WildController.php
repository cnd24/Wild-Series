<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Program;
use App\Entity\Category;
use App\Entity\Season;
use App\Entity\Episode;
use App\Form\CommentType;
use App\Form\ProgramSearchType;
use App\Form\CategoryType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{
    /**
     * @Route("/wild", name="wild_index")
     */
    public function index(Request $request) :Response
    {
        $programs = $this->getDoctrine()->getRepository(Program::class)->findAll();

        if(!$programs) {
            throw $this->createNotFoundException('No program found in program\'s table.');
        }

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
             $categoryManager = $this->getDoctrine()->getManager();
            $categoryManager->persist($category);
            $categoryManager->flush();

            return $this->redirectToRoute('wild_index');
        }

        return $this->render('wild/index.html.twig', [
            'programs' => $programs,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Getting a program with a formatted slug for title
     *
     * @param string $slug The slugger
     * @Route("/wild/show/{slug<^[a-z0-9-]+$>}", defaults={"slug" = null}, name="show_program")
     * @return Response
     */
    public function showByProgram(?string $slug):Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }
        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy([ 'program' => $program]);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with '.$slug.' title, found in program\'s table.'
            );
        }

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug'  => $slug,
            'seasons' => $seasons,
        ]);
    }

    /**
     * @Route("/wild/showBySeason/{id}", name="show_season")
     */
    public function showBySeason(int $id) :Response
    {
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->find($id);

        $program = $season->getProgram();

        $episodes = $season->getEpisodes();

        return $this->render('wild/episodes.html.twig', [
            'season' => $season,
            'program' => $program,
            'episodes' => $episodes,
        ]);

    }


    /**
     * @Route("/category/{categoryName}", name="show_category")
     */

    public function showByCategory(string $categoryName) :Response
    {

        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => $categoryName]);

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' => $category], ['id' => 'desc'], 3);


        return $this->render('wild/category.html.twig', [
            'programs' => $programs,
            'category'  => $category,
        ]);
    }


    /**
     * @Route("/wild/episode/{id}", name="show_episode")
     */

    public function showEpisode(Episode $episode, Request $request, CommentRepository $commentRepository) :Response
    {
        $season = $episode->getSeason();
        $program = $season->getProgram();

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $comment->setAuthor($this->getUser());
            $comment->setEpisode($episode);
            $entityManager->persist($comment);
            $entityManager->flush();
        }

        return $this->render('wild/episode.html.twig', [
            'episode' => $episode,
            'season' => $season,
            'program' => $program,
            'comments' => $commentRepository->findBy(['episode' => $episode]),
            'form' => $form->createView(),
        ]);
    }

} 