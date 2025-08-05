<?php

namespace App\Controller;

use App\Repository\BienRepository;
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
    public function index(BienRepository $bienRepository, Request $request, PaginatorInterface $paginator): Response
    {
        // Capturo todos los posibles filtros enviados por GET desde la interfaz
        $typeDeBien  = $request->query->get('typeDeBien');
        $ville       = $request->query->get('ville');
        $rangoPrecio     = $request->query->get('rangoPrecio');
        $rangoSuperficie = $request->query->get('rangoSuperficie');
        $etatDuBien  = $request->query->get('etatDuBien');
        $tipoTransaccion = $request->query->get('tipoTransaccion');

        // Compatibilidad con redirección desde otras páginas (ej: ?tipo=arriendo)
        if (!$tipoTransaccion && $request->query->get('tipo')) {
            $tipoTransaccion = $request->query->get('tipo');
        }

        // Obtengo valores únicos para los selects de filtro (tipo, ciudad, estado)
        $tiposDeBien = $bienRepository->createQueryBuilder('b')
            ->select('DISTINCT b.typeDeBien')
            ->getQuery()->getResult();
        $tiposDeBien = array_map(fn($row) => $row['typeDeBien'], $tiposDeBien);

        $ciudadesDisponibles = $bienRepository->createQueryBuilder('b')
            ->select('DISTINCT b.ville')
            ->getQuery()->getResult();
        $ciudadesDisponibles = array_map(fn($row) => $row['ville'], $ciudadesDisponibles);

        $estadosDeBien = $bienRepository->createQueryBuilder('b')
            ->select('DISTINCT b.etatDuBien')
            ->getQuery()->getResult();
        $estadosDeBien = array_map(fn($row) => $row['etatDuBien'], $estadosDeBien);

        // Construyo el query aplicando los filtros seleccionados por el usuario
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
        if ($rangoPrecio) {
            [$min, $max] = explode('-', $rangoPrecio);
            $qb->andWhere('b.prix BETWEEN :minPrix AND :maxPrix')
               ->setParameter('minPrix', $min)
               ->setParameter('maxPrix', $max);
        }
        if ($rangoSuperficie) {
            [$min, $max] = explode('-', $rangoSuperficie);
            $qb->andWhere('b.surfaceM2 BETWEEN :minSuperficie AND :maxSuperficie')
               ->setParameter('minSuperficie', $min)
               ->setParameter('maxSuperficie', $max);
        }
        if ($tipoTransaccion) {
            $qb->andWhere('b.tipoTransaccion = :tipoTransaccion')
               ->setParameter('tipoTransaccion', $tipoTransaccion);
        }

        // Pagino los resultados con KnpPaginator para optimizar la UX
        $page = $request->query->getInt('page', 1);
        $bienes = $paginator->paginate($qb, $page, 9); // 9 inmuebles por página

        // Envío a la vista todas las variables que el frontend necesita
        return $this->render('home/index.html.twig', [
            'bienes' => $bienes,
            'tiposDeBien' => $tiposDeBien,
            'ciudadesDisponibles' => $ciudadesDisponibles,
            'estadosDeBien' => $estadosDeBien,
        ]);
    }
}
