<?php

namespace App\Controller\Backend;

use App\Entity\Documento;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DocumentoController extends AbstractController
{
    /**
     * RUTA DE DESCARGA SEGURA:
     * Verifica que el usuario sea el dueño del documento O tenga rol de Administrador.
     */
    #[Route('/documento/descargar/{id}', name: 'documento_descargar', methods: ['GET'])]
    public function descargar(Documento $documento): Response
    {
        $usuarioActual = $this->getUser();

        // REGLA DE ORO DE SEGURIDAD
        if ($documento->getPropietario() !== $usuarioActual && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('No tienes autorización para ver este documento confidencial.');
        }

        $directorioSeguro = $this->getParameter('propietarios_documentos_directory');
        $rutaCompleta = $directorioSeguro . '/' . $documento->getNombreArchivo();

        if (!file_exists($rutaCompleta)) {
            throw $this->createNotFoundException('El archivo físico no se encuentra en el servidor.');
        }

        $response = new BinaryFileResponse($rutaCompleta);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            $documento->getNombreOriginal()
        );

        return $response;
    }

    /**
     * VISTA VIP PARA TU HERMANO (ADMINISTRADOR):
     * Usamos el EntityManager para buscar los documentos sin necesitar un DocumentoRepository.
     */
    #[Route('/admin/documentos-propietarios', name: 'admin_documentos_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function indexAdmin(UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        // Buscamos solo a los usuarios que tengan el rol de propietario
        $propietarios = $userRepository->createQueryBuilder('u')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%ROLE_PROPRIETAIRE%')
            ->getQuery()
            ->getResult();

        $documentosPorPropietario = [];
        foreach ($propietarios as $propietario) {
            // Buscamos los documentos usando el repositorio genérico del EntityManager
            $documentos = $em->getRepository(Documento::class)->findBy(
                ['propietario' => $propietario],
                ['fechaSubida' => 'DESC']
            );

            $documentosPorPropietario[$propietario->getId()] = [
                'usuario' => $propietario,
                'documentos' => $documentos
            ];
        }

        return $this->render('admin/documentos/index.html.twig', [
            'propietariosData' => $documentosPorPropietario
        ]);
    }
}