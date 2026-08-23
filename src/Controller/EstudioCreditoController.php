<?php

namespace App\Controller;

use App\Entity\EstudioCliente;
use App\Form\EstudioClienteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EstudioCreditoController extends AbstractController
{
    #[Route('/estudio-credito', name: 'estudio_credito', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $estudio = new EstudioCliente();
        $form = $this->createForm(EstudioClienteType::class, $estudio);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($estudio);
            $em->flush();
            
            $this->addFlash('success', '¡Tu solicitud de viabilidad ha sido enviada con éxito! Un asesor de CONSORIA se pondrá en contacto contigo.');
            
            // Recarga la página para limpiar el formulario tras enviarlo
            return $this->redirectToRoute('estudio_credito');
        }

        return $this->render('estudio_credito/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}