<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function home(): Response
    {
        return $this->render('thepages/home.html.twig');
    }

    #[Route('/apropos', name: 'app_home.aboutus', methods: ['GET'])]
    public function apropos(): Response
    {
        return $this->render('thepages/aboutus.html.twig');
    }

    #[Route('/politiquedeconfidentialite', name: 'app_privacypolicy', methods: ['GET'])]
    public function politiquedeconfidentialite(): Response
    {
        return $this->render('thepages/privacypolicy.html.twig');
    }

    #[Route('/mentionslegales', name: 'app_legalnotice', methods: ['GET'])]
    public function mentionslegales(): Response
    {
        return $this->render('thepages/legalnotice.html.twig');
    }

    #[Route('/conditionsgeneralesdutilisation', name: 'app_termsconditionsuse', methods: ['GET'])]
    public function conditionsgeneralesdutilisation(): Response
    {
        return $this->render('thepages/termsconditionsuse.html.twig');
    }
}
