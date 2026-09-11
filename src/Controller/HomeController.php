<?php

namespace App\Controller;

use App\Repository\BienRepository;
use App\Repository\TestimonioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class HomeController extends AbstractController
{
    /**
     * Ruta principal ("/") de la app: muestra el listado de inmuebles con filtros.
     * Soporta redirección directa desde páginas como /rent con el parámetro ?tipo=arriendo
     */
    #[Route('/', name: 'home')]
    public function index(BienRepository $bienRepository, TestimonioRepository $testimonioRepository, Request $request, PaginatorInterface $paginator): Response
    {
        // 1. Captura y normalización de parámetros GET
        $typeDeBien      = $request->query->get('typeDeBien');
        $ville           = $request->query->get('ville');
        $rangoPrecio     = $request->query->get('rangoPrecio');
        $rangoSuperficie = $request->query->get('rangoSuperficie');
        $etatDuBien      = $request->query->get('etatDuBien');
        $tipoTransaccion = $request->query->get('tipoTransaccion');

        // Compatibilidad con redirección externa (?tipo=arriendo)
        if (!$tipoTransaccion && $request->query->get('tipo')) {
            $tipoTransaccion = $request->query->get('tipo');
        }

        // 2. Extracción limpia de filtros únicos desde el repositorio (Evitamos lógica pesada aquí)
        $tiposDeBien         = $bienRepository->findDistinctValues('typeDeBien');
        $ciudadesDisponibles = $bienRepository->findDistinctValues('ville');
        $estadosDeBien       = $bienRepository->findDistinctValues('etatDuBien');

        // 3. Centralización y construcción segura del QueryBuilder
        $qb = $bienRepository->createQueryBuilder('b');

        if ($typeDeBien) {
            $qb->andWhere('b.typeDeBien = :typeDeBien')
               ->setParameter('typeDeBien', $typeDeBien);
        }
        if ($ville) {
            $qb->andWhere('b.ville = :ville')
               ->setParameter('ville', $ville);
        }
        if ($etatDuBien) {
            $qb->andWhere('b.etatDuBien = :etatDuBien')
               ->setParameter('etatDuBien', $etatDuBien);
        }
        if ($tipoTransaccion) {
            $qb->andWhere('b.tipoTransaccion = :tipoTransaccion')
               ->setParameter('tipoTransaccion', $tipoTransaccion);
        }

        // BLINDAJE ANTI-CRASH: Validación segura del formato de rangos (Precio)
        if ($rangoPrecio && str_contains($rangoPrecio, '-')) {
            $partesPrecio = explode('-', $rangoPrecio);
            if (count($partesPrecio) === 2 && is_numeric($partesPrecio[0]) && is_numeric($partesPrecio[1])) {
                $qb->andWhere('b.prix BETWEEN :minPrix AND :maxPrix')
                   ->setParameter('minPrix', $partesPrecio[0])
                   ->setParameter('maxPrix', $partesPrecio[1]);
            }
        }

        // BLINDAJE ANTI-CRASH: Validación segura del formato de rangos (Superficie)
        if ($rangoSuperficie && str_contains($rangoSuperficie, '-')) {
            $partesSuperficie = explode('-', $rangoSuperficie);
            if (count($partesSuperficie) === 2 && is_numeric($partesSuperficie[0]) && is_numeric($partesSuperficie[1])) {
                $qb->andWhere('b.surfaceM2 BETWEEN :minSuperficie AND :maxSuperficie')
                   ->setParameter('minSuperficie', $partesSuperficie[0])
                   ->setParameter('maxSuperficie', $partesSuperficie[1]);
            }
        }

        // Ordenamos por defecto para que los últimos inmuebles publicados aparezcan primero
        $qb->orderBy('b.id', 'DESC');

        // 4. Paginación inteligente a nivel de base de datos
        $page = $request->query->getInt('page', 1);
        $bienes = $paginator->paginate($qb, $page, 9);

        // 5. Extracción de testimonios activos para el carrusel de la página de inicio
        $testimonios = $testimonioRepository->findBy(['activo' => true]);

        // 6. Envío de datos idéntico a tu Twig original para no romper el diseño gráfico
        return $this->render('home/index.html.twig', [
            'bienes' => $bienes,
            'tiposDeBien' => $tiposDeBien,
            'ciudadesDisponibles' => $ciudadesDisponibles,
            'estadosDeBien' => $estadosDeBien,
            'testimonios' => $testimonios,
        ]);
    }
}