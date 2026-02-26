<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Repository\FavoritoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Este controlador se encarga únicamente de mostrar el detalle de un bien.
 * Así separo claramente la responsabilidad de cada controlador según buenas prácticas.
 */
#[Route('/bien')]
class BienController extends AbstractController
{
    /**
     * Ruta para ver el detalle de un bien específico.
     * Aquí paso la variable esFavorito al Twig para personalizar la UI del botón favoritos.
     */
    #[Route('/{id}', name: 'bien_show')]
    public function show(Bien $bien, FavoritoRepository $favoritoRepository): Response
    {
        // Verifico si el usuario autenticado es cliente y si este bien ya está en sus favoritos
        $esFavorito = false;
        if ($this->getUser() && in_array('ROLE_CLIENT', $this->getUser()->getRoles())) {
            $favorito = $favoritoRepository->findOneBy([
                'user' => $this->getUser(),
                'bien' => $bien,
            ]);
            $esFavorito = $favorito !== null;
        }

        // Renderizo la vista de detalle con la variable esFavorito para el corazón en la interfaz
        return $this->render('bien/show.html.twig', [
            'bien' => $bien,
            'esFavorito' => $esFavorito,
        ]);
    }
}
