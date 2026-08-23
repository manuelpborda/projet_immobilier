<?php

namespace App\Controller\Frontend;

use App\Entity\Bien;
use App\Entity\Offre;
use App\Entity\Visite;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class InteractionController extends AbstractController
{
    #[Route('/bien/{id}/solicitar-visita', name: 'solicitar_visita', methods: ['POST'])]
    public function solicitarVisita(Bien $bien, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CLIENT');

        $fecha = $request->request->get('fecha_visita');
        $comentarios = $request->request->get('comentarios');

        if ($fecha) {
            $visite = new Visite();
            $visite->setClient($this->getUser());
            $visite->setBien($bien);
            $visite->setDateVisite(new \DateTime($fecha));
            $visite->setCommentaires($comentarios);
            $visite->setStatut('Programada'); // Estado por defecto

            $em->persist($visite);
            $em->flush();

            $this->addFlash('success', '¡Tu solicitud de visita ha sido enviada con éxito! Nos contactaremos pronto.');
        } else {
            $this->addFlash('error', 'Debes seleccionar una fecha válida.');
        }

        return $this->redirectToRoute('bien_show', ['id' => $bien->getId()]);
    }

    #[Route('/bien/{id}/enviar-oferta', name: 'enviar_oferta', methods: ['POST'])]
    public function enviarOferta(Bien $bien, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CLIENT');

        $precio = $request->request->get('precio_oferta');
        $condiciones = $request->request->get('condiciones');

        if ($precio && is_numeric($precio)) {
            $offre = new Offre();
            $offre->setClient($this->getUser());
            $offre->setBien($bien);
            $offre->setPrix($precio);
            $offre->setConditionsAchat($condiciones);
            $offre->setDateOffre(new \DateTime());
            $offre->setEtatNegociation('Presentada'); // Estado por defecto

            $em->persist($offre);
            $em->flush();

            $this->addFlash('success', '¡Tu oferta ha sido enviada al propietario! Te notificaremos cualquier actualización.');
        } else {
            $this->addFlash('error', 'Debes ingresar un monto válido para la oferta.');
        }

        return $this->redirectToRoute('bien_show', ['id' => $bien->getId()]);
    }
}