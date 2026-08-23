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
        // OPTIMIZACIÓN COMERCIAL: Generación de enlace dinámico de WhatsApp
        // =========================================================================
        $propietario = $bien->getProprietaire();
        
        // CORRECCIÓN DE PROPIEDAD: Cambiado getTelephone() por getPhone() según mapeo de RegistrationFormType
        $telefono = ($propietario && method_exists($propietario, 'getPhone') && $propietario->getPhone())
            ? $propietario->getPhone()
            : '573000000000'; // Teléfono corporativo de respaldo (Código de país 57 para Colombia)

        // Limpiamos el teléfono de espacios, guiones o caracteres extraños
        $telefonoLimpio = preg_replace('/[^0-9]/', '', $telefono);

        // Si el teléfono guardado en Colombia no incluye el código de país (57), se lo anteponemos automáticamente
        if (strlen($telefonoLimpio) === 10 && strpos($telefonoLimpio, '3') === 0) {
            $telefonoLimpio = '57' . $telefonoLimpio;
        }

        // Creamos un texto predefinido atractivo para el mercado local
        $mensajeBase = sprintf(
            "Hola, estoy interesado en el inmueble (%s) ubicado en %s, con un valor de $%s COP. Me gustaría recibir más información. ¡Gracias!",
            $bien->getTypeDeBien() ?? 'Inmueble',
            $bien->getVille() ?? 'Colombia',
            number_format((float)($bien->getPrix() ?? 0), 0, ',', '.')
        );

        $whatsappUrl = "https://wa.me/" . $telefonoLimpio . "?text=" . urlencode($mensajeBase);
        // =========================================================================

        // Renderizo la vista de detalle conservando tus variables originales intactas
        return $this->render('bien/show.html.twig', [
            'bien' => $bien,
            'esFavorito' => $esFavorito,
            'whatsappUrl' => $whatsappUrl,
        ]);
    }
}