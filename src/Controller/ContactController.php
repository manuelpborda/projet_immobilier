<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
// IMPORTANTE: No incluyo la entidad Contact aún, la crearemos después

class ContactController extends AbstractController
{
    // Esta ruta muestra el formulario de contacto vacío
    // Lo llamo desde la url /contact
    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        // Simplemente muestro el formulario por ahora (Twig lo haremos luego)
        return $this->render('static/contact.html.twig');
    }

    // Esta ruta manejará el envío POST del formulario (cuando el usuario envía el mensaje)
    #[Route('/contact/post', name: 'contact_post', methods: ['POST'])]
    public function contactPost(Request $request, EntityManagerInterface $em, CsrfTokenManagerInterface $csrf): Response
    {
        // Por ahora, solo muestro de nuevo el formulario, pronto añadiremos la lógica
        // Aquí se manejarán validaciones, seguridad, y guardado en BD
        return $this->render('static/contact.html.twig');
    }
}