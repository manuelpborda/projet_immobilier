<?php

namespace App\Controller\Admin;

use App\Entity\Offre;
use App\Entity\Visite;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/interacciones')]
final class InteraccionesController extends AbstractController
{
    #[Route('/', name: 'admin_interacciones_index')]
    public function index(EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $visitas = $em->getRepository(Visite::class)->findBy([], ['dateVisite' => 'DESC']);
        $ofertas = $em->getRepository(Offre::class)->findBy([], ['dateOffre' => 'DESC']);

        return $this->render('admin/interacciones/index.html.twig', [
            'visitas' => $visitas,
            'ofertas' => $ofertas,
        ]);
    }

    #[Route('/visita/{id}/estado/{estado}', name: 'admin_visita_estado')]
    public function cambiarEstadoVisita(Visite $visita, string $estado, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $visita->setStatut($estado);
        $em->flush();
        
        $this->addFlash('success', 'El estado de la visita ha sido actualizado a: ' . $estado);
        return $this->redirectToRoute('admin_interacciones_index');
    }

    #[Route('/oferta/{id}/estado/{estado}', name: 'admin_oferta_estado')]
    public function cambiarEstadoOferta(Offre $oferta, string $estado, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $oferta->setEtatNegociation($estado);
        $em->flush();
        
        $this->addFlash('success', 'El estado de la oferta ha sido actualizado a: ' . $estado);
        return $this->redirectToRoute('admin_interacciones_index');
    }
}