<?php

namespace App\Controller;

use App\Repository\BienRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

use App\Entity\Bien;

class HomeController extends AbstractController
{
    // Esta es la ruta principal ("/") de la aplicación, muestra el listado de inmuebles con filtros.
    #[Route('/', name: 'home')]
    public function index(BienRepository $bienRepository, Request $request, PaginatorInterface $paginator): Response
    {
        // === 1. PRIMERO: Capturo los parámetros de filtro que vienen por GET ===
        // Nota: Si el usuario filtra desde el formulario, llegan por la URL, por eso uso $request->query->get().
        $typeDeBien  = $request->query->get('typeDeBien');    // Tipo de bien (Apartamento, Casa, etc)
        $ville       = $request->query->get('ville');         // Ciudad
        $rangoPrecio     = $request->query->get('rangoPrecio');
        $rangoSuperficie = $request->query->get('rangoSuperficie');
        $etatDuBien  = $request->query->get('etatDuBien');    // Estado del bien (nuevo, antiguo...)
        $tipoTransaccion = $request->query->get('tipoTransaccion'); // Venta, arriendo o vacacional. Nuevo filtro integrado.

        // Si más adelante agrego más filtros (ejemplo: número de habitaciones, baños, etc.), los capturo aquí igual.

        // === 2. OBTENGO los valores únicos para los selects (tipos, ciudades, estados) desde la base ===

        // Esto me asegura que los valores del filtro siempre estén sincronizados con los datos reales de la BD.

        // Tipos de bien para el select
        $tiposDeBien = $bienRepository->createQueryBuilder('b')
            ->select('DISTINCT b.typeDeBien')
            ->getQuery()->getResult();
        $tiposDeBien = array_map(fn($row) => $row['typeDeBien'], $tiposDeBien);

        // Ciudades únicas para el select de ciudades
        $ciudadesDisponibles = $bienRepository->createQueryBuilder('b')
            ->select('DISTINCT b.ville')
            ->getQuery()->getResult();
        $ciudadesDisponibles = array_map(fn($row) => $row['ville'], $ciudadesDisponibles);

        // Estados únicos (nuevo, renovado, antiguo, etc)
        $estadosDeBien = $bienRepository->createQueryBuilder('b')
            ->select('DISTINCT b.etatDuBien')
            ->getQuery()->getResult();
        $estadosDeBien = array_map(fn($row) => $row['etatDuBien'], $estadosDeBien);

        // === 3. CONSTRUYO el QueryBuilder aplicando los filtros seleccionados ===
        // Nota: Este es el core del filtrado. Si un filtro está vacío, no lo aplico.
        $qb = $bienRepository->createQueryBuilder('b');

        // Filtro por tipo de bien si el usuario seleccionó uno
        if ($typeDeBien) {
            $qb->andWhere('b.typeDeBien = :typeDeBien')
               ->setParameter('typeDeBien', $typeDeBien);
        }

        // Filtro por ciudad si se seleccionó
        if ($ville) {
            $qb->andWhere('b.ville = :ville')
               ->setParameter('ville', $ville);
        }

        // Filtro por estado (nuevo, renovado, etc)
        if ($etatDuBien) {
            $qb->andWhere('b.etatDuBien = :etatDuBien')
               ->setParameter('etatDuBien', $etatDuBien);
        }

        // --- Filtro por rango de precio
        if ($rangoPrecio) {
            [$min, $max] = explode('-', $rangoPrecio);
            $qb->andWhere('b.prix BETWEEN :minPrix AND :maxPrix')
                ->setParameter('minPrix', $min)
                ->setParameter('maxPrix', $max);
        }

        // --- Filtro por rango de superficie
        if ($rangoSuperficie) {
            [$min, $max] = explode('-', $rangoSuperficie);
            $qb->andWhere('b.surfaceM2 BETWEEN :minSuperficie AND :maxSuperficie')
                ->setParameter('minSuperficie', $min)
                ->setParameter('maxSuperficie', $max);
        }

        // -- Filtro por tipo de transacción (venta, arriendo, vacacional)
        // Nota: Aquí agrego el filtro usando el campo tipoTransaccion de la entidad Bien
        if ($tipoTransaccion) {
            $qb->andWhere('b.tipoTransaccion = :tipoTransaccion')
                ->setParameter('tipoTransaccion', $tipoTransaccion);
        }

        // IMPORTANTE: Si agrego nuevos filtros, solo tengo que seguir el patrón de arriba.
        // Ejemplo: Para filtrar por número de habitaciones, agregaría aquí y en el formulario.

        // === 4. PAGINO los resultados para que no se sobrecargue la página ===
        // Uso el bundle KnpPaginator para paginar el resultado del query builder.
        $page = $request->query->getInt('page', 1); // Por defecto, la página es 1
        $bienes = $paginator->paginate(
            $qb,    // El query builder con todos los filtros aplicados
            $page,  // Página actual
            8       // Número de elementos por página
        );

        // === 5. ENVÍO a la vista (Twig) todas las variables que voy a usar en el frontend ===
        return $this->render('home/index.html.twig', [
            'bienes' => $bienes,                        // Lista paginada de inmuebles
            'tiposDeBien' => $tiposDeBien,              // Opciones para el filtro tipo
            'ciudadesDisponibles' => $ciudadesDisponibles, // Opciones para filtro ciudad
            'estadosDeBien' => $estadosDeBien,          // Opciones para filtro estado
            // Si agrego más filtros, los paso aquí
        ]);
    }

    // Ruta y método para ver el detalle de un inmueble
    // NOTA: Aquí recibo el objeto Bien automáticamente gracias a ParamConverter
    #[Route('/bien/{id}', name: 'bien_show')]
    public function show(Bien $bien): Response
    {
        // Renderiza la página de detalle, usando la vista bien/show.html.twig
        return $this->render('bien/show.html.twig', [
            'bien' => $bien,
        ]);
    }
   

}
