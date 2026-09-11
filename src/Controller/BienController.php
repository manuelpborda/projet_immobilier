<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Repository\FavoritoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Este controlador se encarga de mostrar el detalle de un bien 
 * y de gestionar acciones específicas del bien.
 */
#[Route('/bien')]
class BienController extends AbstractController
{
    /**
     * Ruta para ver el detalle de un bien específico.
     * Aquí paso la variable esFavorito al Twig para personalizar la UI del botón favoritos.
     */
    #[Route('/{id}', name: 'bien_show', methods: ['GET'])]
    public function show(Bien $bien, FavoritoRepository $favoritoRepository): Response
    {
        // CORRECCIÓN DE SEGURIDAD: Cambiamos in_array por isGranted para respetar la jerarquía
        // de roles definida en config/packages/security.yaml
        $esFavorito = false;
        if ($this->getUser() && $this->isGranted('ROLE_CLIENT')) {
            $favorito = $favoritoRepository->findOneBy([
                'user' => $this->getUser(),
                'bien' => $bien,
            ]);
            $esFavorito = $favorito !== null;
        }

        // =========================================================================
        // OPTIMIZACIÓN COMERCIAL: Enlace de WhatsApp directo al Asesor (Tu hermano)
        // =========================================================================
        
        // Número oficial de la agencia/asesor (Código Colombia 57 + Celular)
        $telefonoAsesor = '573124504982';

        // Creamos un texto predefinido atractivo para el mercado local
        $mensajeBase = sprintf(
            "Hola, estoy interesado en el inmueble (%s) ubicado en %s, con un valor de $%s COP. Me gustaría recibir más información. ¡Gracias!",
            $bien->getTypeDeBien() ?? 'Inmueble',
            $bien->getVille() ?? 'Colombia',
            number_format((float)($bien->getPrix() ?? 0), 0, ',', '.')
        );

        $whatsappUrl = "https://wa.me/" . $telefonoAsesor . "?text=" . urlencode($mensajeBase);
        // =========================================================================

        // Renderizo la vista de detalle conservando tus variables originales intactas
        return $this->render('bien/show.html.twig', [
            'bien' => $bien,
            'esFavorito' => $esFavorito,
            'whatsappUrl' => $whatsappUrl,
        ]);
    }

    /**
     * Ruta para que el administrador marque el inmueble como Vendido/Arrendado
     */
    #[Route('/{id}/toggle-vendido', name: 'admin_toggle_vendido', methods: ['POST'])]
    public function toggleVendido(Bien $bien, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Solo permitimos esta acción si el usuario tiene permisos (ej. tu hermano)
        $this->denyAccessUnlessGranted('ROLE_PROPRIETAIRE');

        // Cambia el estado (si era falso lo pasa a verdadero y viceversa)
        // Nota: Asegúrate de que los getters/setters generados en tu entidad sean isVendido() o getVendido()
        $nuevoEstado = !$bien->isVendido();
        $bien->setVendido($nuevoEstado);
        
        $entityManager->flush();

        $textoEstado = $nuevoEstado ? 'marcado como no disponible (Vendido/Arrendado)' : 'marcado como disponible nuevamente';
        $this->addFlash('success', 'El inmueble ha sido ' . $textoEstado . '.');

        // Devuelve al administrador a la página donde estaba (con un fallback de seguridad)
        $referer = $request->headers->get('referer');
        return $this->redirect($referer ?: $this->generateUrl('app_dashboard'));
    }
}