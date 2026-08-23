<?php 

namespace App\Controller\Frontend;

use App\Repository\BienRepository;
use App\Repository\FavoritoRepository;
use App\Entity\Favorito;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

final class FavorisController extends AbstractController
{
    // Página principal de favoritos para clientes autenticados
    #[Route('/favoritos', name: 'favoritos_index')]
    public function index(FavoritoRepository $favoritoRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CLIENT');

        $favoritos = $favoritoRepository->findBy(
            ['user' => $this->getUser()],
            ['createdAt' => 'DESC']
        );

        return $this->render('favoris/index.html.twig', [
            'favoritos' => $favoritos,
        ]);
    }

    // Acción para agregar un bien a favoritos
    #[Route('/favorito/agregar/{id}', name: 'agregar_favorito', methods: ['POST'])]
    public function agregarFavorito(
        int $id,
        BienRepository $bienRepository,
        EntityManagerInterface $em,
        FavoritoRepository $favoritoRepository,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_CLIENT');
        $user = $this->getUser();
        $bien = $bienRepository->find($id);

        $isAjax = $request->headers->get('X-Requested-With') === 'XMLHttpRequest' || 
                  str_contains($request->headers->get('Accept', ''), 'application/json');

        if (!$bien) {
            if ($isAjax) {
                return $this->json(['success' => false, 'message' => 'Bien no encontrado']);
            }
            $this->addFlash('error', 'El bien no existe.');
            return $this->redirectToRoute('favoritos_index');
        }

        if ($favoritoRepository->findOneBy(['user' => $user, 'bien' => $bien])) {
            if ($isAjax) {
                return $this->json(['success' => false, 'message' => 'Ya es favorito']);
            }
            $this->addFlash('info', 'Este bien ya está en tus favoritos.');
            return $this->redirectToRoute('favoritos_index');
        }

        $favorito = new Favorito();
        $favorito->setUser($user);
        $favorito->setBien($bien);
        $em->persist($favorito);
        $em->flush();

        if ($isAjax) {
            return $this->json(['success' => true, 'message' => 'Favorito agregado']);
        }

        $this->addFlash('success', 'Inmueble agregado a favoritos.');
        return $this->redirectToRoute('favoritos_index');
    }

    // Acción para quitar un bien de favoritos
    #[Route('/favorito/quitar/{id}', name: 'quitar_favorito', methods: ['POST'])]
    public function quitarFavorito(
        int $id,
        BienRepository $bienRepository,
        EntityManagerInterface $em,
        FavoritoRepository $favoritoRepository,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_CLIENT');
        $user = $this->getUser();
        $bien = $bienRepository->find($id);

        $isAjax = $request->headers->get('X-Requested-With') === 'XMLHttpRequest' || 
                  str_contains($request->headers->get('Accept', ''), 'application/json');

        if (!$bien) {
            if ($isAjax) {
                return $this->json(['success' => false, 'message' => 'Bien no encontrado']);
            }
            $this->addFlash('error', 'El inmueble no existe.');
            return $this->redirectToRoute('favoritos_index');
        }

        $favorito = $favoritoRepository->findOneBy(['user' => $user, 'bien' => $bien]);

        if ($favorito) {
            $em->remove($favorito);
            $em->flush();
            if ($isAjax) {
                return $this->json(['success' => true, 'message' => 'Favorito quitado']);
            }
            $this->addFlash('success', 'Inmueble retirado de tus favoritos.');
        } else {
            if ($isAjax) {
                return $this->json(['success' => false, 'message' => 'No era favorito']);
            }
            $this->addFlash('warning', 'Este inmueble no estaba en tus favoritos.');
        }

        return $this->redirectToRoute('favoritos_index');
    }
}