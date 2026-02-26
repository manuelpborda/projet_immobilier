<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class StaticPagesController extends AbstractController
{
    #[Route('/buy', name: 'buy', methods: ['GET'])]
    public function buy(): Response
    {
        return $this->render('static/buy.html.twig');
    }

    #[Route('/sell', name: 'sell', methods: ['GET'])]
    public function sell(): Response
    {
        return $this->render('static/sell.html.twig');
    }

    #[Route('/rent', name: 'rent', methods: ['GET'])]
    public function rent(): Response
    {
        return $this->render('static/rent.html.twig');
    }

    #[Route('/vacation', name: 'vacation', methods: ['GET'])]
    public function vacation(): Response
    {
        return $this->render('static/vacation.html.twig');
    }

    #[Route('/about', name: 'about', methods: ['GET'])]
    public function about(): Response
    {
        return $this->render('static/about.html.twig');
    }

    #[Route('/mentions-legales', name: 'mentions_legales', methods: ['GET'])]
    public function mentionsLegales(): Response
    {
        return $this->render('mentions_legales.html.twig');
    }
}
