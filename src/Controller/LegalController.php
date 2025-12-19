<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LegalController extends AbstractController
{
    #[Route('/legal/mentions-legales', name: 'app_legal_mentions')]
    public function mentions(): Response
    {
        // affichage des mentions
        return $this->render('legal/mentions_legales.html.twig');
    }

    #[Route('/legal/cgu', name: 'app_legal_cgu')]
    public function cgu(): Response
    {
        // les condition general
        return $this->render('legal/cgu.html.twig');
    }

    #[Route('/legal/cgv', name: 'app_legal_cgv')]
    public function cgv(): Response
    {
        // cgv page
        return $this->render('legal/cgv.html.twig');
    }

    #[Route('/legal/politique-confidentialite', name: 'app_legal_privacy')]
    public function privacy(): Response
    {
        // la vie priver
        return $this->render('legal/politique_confidentialite.html.twig');
    }

    #[Route('/legal/qui-sommes-nous', name: 'app_legal_about')]
    public function about(): Response
    {
        // about us page
        return $this->render('legal/qui_sommes_nous.html.twig');
    }
}
