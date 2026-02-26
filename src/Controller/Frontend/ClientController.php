<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ClientController extends AbstractController
{
    #[Route('/client/dashboard', name: 'client_dashboard')]
    public function dashboard(): Response
    {
        // Este método muestra el panel principal del cliente después del login
        return $this->render('client/dashboard.html.twig', [
            'user' => $this->getUser(), // Paso el usuario autenticado a la vista
        ]);
    }
}
