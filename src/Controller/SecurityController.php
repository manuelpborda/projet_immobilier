<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // CONTROL DE ACCESO BACKEND: Si el usuario ya está autenticado, 
        // lo desviamos para que no vuelva a ver el formulario de login.
        if ($this->getUser()) {
            // Reutilizamos la ruta de detalle o la que consideres tu Home principal
            return $this->redirectToRoute('bien_show', ['id' => 1]); 
        }

        // Obtiene el error de inicio de sesión si existe alguno
        $error = $authenticationUtils->getLastAuthenticationError();

        // Último nombre de usuario (email) introducido por el usuario
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error, // Tu plantilla Twig procesará esto exactamente igual
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}