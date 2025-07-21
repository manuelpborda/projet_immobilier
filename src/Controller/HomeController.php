<?php

namespace App\Controller;

use App\Repository\BienRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Bien;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(BienRepository $bienRepository, Request $request): Response
    {
        // 1. Obtener parámetros de búsqueda
        $search = $request->query->get('search');
        $typeDeBien = $request->query->get('typeDeBien');

        // 2. Obtener tipos únicos para el select
        $tiposDeBien = $bienRepository->createQueryBuilder('b')
            ->select('DISTINCT b.typeDeBien')
            ->getQuery()
            ->getResult();
        $tiposDeBien = array_map(fn($row) => $row['typeDeBien'], $tiposDeBien);

        // 3. Construir la consulta de filtrado dinámico
        $qb = $bienRepository->createQueryBuilder('b');
        if ($search) {
            $qb->andWhere('b.adresse LIKE :search OR b.etatDuBien LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }
        if ($typeDeBien) {
            $qb->andWhere('b.typeDeBien = :typeDeBien')
               ->setParameter('typeDeBien', $typeDeBien);
        }
        $bienes = $qb->getQuery()->getResult();

        // 4. Obtener todos los estados únicos (por ejemplo, Neuf, Ancien, etc.)
        $estadosDeBien = $bienRepository->createQueryBuilder('b')
            ->select('DISTINCT b.etatDuBien')
            ->getQuery()
            ->getResult();
        $estadosDeBien = array_map(fn($row) => $row['etatDuBien'], $estadosDeBien);


        // 5. Pasar datos a la vista
        return $this->render('home/index.html.twig', [
            'bienes' => $bienes,
            'tiposDeBien' => $tiposDeBien,
            'estadosDeBien' => $estadosDeBien,
        ]);
    }

    #[Route('/bien/{id}', name: 'bien_show')]
public function show(Bien $bien): Response
{
    return $this->render('bien/show.html.twig', [
        'bien' => $bien,
    ]);
}

}