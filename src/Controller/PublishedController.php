<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublishedController extends AbstractController
{
    #[Route('/nosrecettes', name: 'app_published', methods: ['GET'])]
    public function index(RecipeRepository $repository): Response
    {
        return $this->render('thepages/published/index.html.twig', [
            'recipes' => $repository->findPublicRecipe(3)
        ]);
    }

}

