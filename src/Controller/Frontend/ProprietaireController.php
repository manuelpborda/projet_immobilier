<?php

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProprietaireController extends AbstractController
{
    #[Route('/proprietaire/dashboard', name: 'proprietaire_dashboard')]
    public function dashboard(): Response
    {
        // Este es el panel del propietario. Se carga automáticamente tras login si typeUser = 'proprietaire'
        return $this->render('proprietaire/dashboard.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}
