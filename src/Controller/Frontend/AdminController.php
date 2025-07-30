<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function dashboard(): Response
    {
        // Este método es el panel principal que verá un usuario con tipo "admin"
        return $this->render('admin/dashboard.html.twig', [
            'user' => $this->getUser(), // Paso el usuario actual a la vista
        ]);
    }
}
