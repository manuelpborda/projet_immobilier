<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaticPagesController extends AbstractController
{
    #[Route('/buy', name: 'buy')]
    public function buy(): Response
    {
        return $this->render('static/buy.html.twig');
    }

    #[Route('/sell', name: 'sell')]
    public function sell(): Response
    {
        return $this->render('static/sell.html.twig');
    }

    #[Route('/rent', name: 'rent')]
    public function rent(): Response
    {
        return $this->render('static/rent.html.twig');
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('static/about.html.twig');
    }

    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('static/contact.html.twig');
    }
}