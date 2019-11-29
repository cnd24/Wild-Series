<?php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Category;
use App\Entity\Season;
use App\Entity\Episode;
use App\Form\ProgramSearchType;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category_add")
     */
    public function add(Request $request): Response
    {

        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $categoryManager = $this->getDoctrine()->getManager();
            $categoryManager->persist($category);
            $categoryManager->flush();

            return $this->redirectToRoute('category_add');
        }

        return $this->render('wild/categories.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
        ]);
    }

}