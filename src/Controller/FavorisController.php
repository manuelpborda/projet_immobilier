<?php

namespace App\Controller;

use App\Repository\BienRepository;
use App\Repository\FavoritoRepository;
use App\Entity\Favorito;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class FavorisController extends AbstractController
{
    // Página principal de favoritos para clientes autenticados
    #[Route('/favoritos', name: 'favoritos_index')]
    public function index(FavoritoRepository $favoritoRepository): Response
    {
        // Solo permito acceso a clientes autenticados
        $this->denyAccessUnlessGranted('ROLE_CLIENT');
        // Obtengo los favoritos del usuario logueado
        $favoritos = $favoritoRepository->findBy(['user' => $this->getUser()]);
        // Los paso a la vista para renderizarlos en la página de favoritos
        return $this->render('favoris/index.html.twig', [
            'favoritos' => $favoritos,
        ]);
    }
    
    // Acción para agregar un bien a favoritos
    #[Route('/favorito/agregar/{id}', name: 'agregar_favorito', methods: ['POST'])]
    public function agregarFavorito(
        $id,
        BienRepository $bienRepository,
        EntityManagerInterface $em,
        FavoritoRepository $favoritoRepository
    ): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_CLIENT');
        $user = $this->getUser();
        $bien = $bienRepository->find($id);

        if (!$bien) {
            return new JsonResponse(['success' => false, 'message' => 'Bien no encontrado'], 404);
        }

        // Evito duplicados buscando si ya existe el favorito
        $favoritoExistente = $favoritoRepository->findOneBy(['user' => $user, 'bien' => $bien]);
        if ($favoritoExistente) {
            return new JsonResponse(['success' => false, 'message' => 'Ya es favorito']);
        }

        $favorito = new Favorito();
        $favorito->setUser($user);
        $favorito->setBien($bien);

        $em->persist($favorito);
        $em->flush();

        return new JsonResponse(['success' => true, 'message' => 'Favorito agregado']);
    }

    // Acción para quitar un bien de favoritos
    #[Route('/favorito/quitar/{id}', name: 'quitar_favorito', methods: ['POST'])]
    public function quitarFavorito(
        $id,
        BienRepository $bienRepository,
        EntityManagerInterface $em,
        FavoritoRepository $favoritoRepository
    ): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_CLIENT');
        $user = $this->getUser();
        $bien = $bienRepository->find($id);

        if (!$bien) {
            return new JsonResponse(['success' => false, 'message' => 'Bien no encontrado'], 404);
        }

        $favorito = $favoritoRepository->findOneBy(['user' => $user, 'bien' => $bien]);
        if ($favorito) {
            $em->remove($favorito);
            $em->flush();
            return new JsonResponse(['success' => true, 'message' => 'Favorito quitado']);
        }
        return new JsonResponse(['success' => false, 'message' => 'No era favorito']);
    }
}
