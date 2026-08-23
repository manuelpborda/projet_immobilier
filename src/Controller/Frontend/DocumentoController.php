<?php

namespace App\Controller\Frontend;

use App\Entity\Documento;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\HeaderUtils;

final class DocumentoController extends AbstractController
{
    // Vista para el Propietario / Cliente (sus propios documentos)
    #[Route('/mis-documentos', name: 'mis_documentos_index')]
    public function index(EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        // Usamos el EntityManager para consultar directamente la Entidad
        $documentos = $em->getRepository(Documento::class)->findBy(
            ['propietario' => $user],
            ['fechaSubida' => 'DESC']
        );

        return $this->render('documento/index.html.twig', [
            'documentos' => $documentos,
        ]);
    }

    // Vista para el Administrador
    #[Route('/admin/documentos', name: 'admin_documentos_index')]
    public function adminIndex(EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Consultamos Usuarios y Documentos dinámicamente
        $propietarios = $em->getRepository(User::class)->findBy(['typeUser' => 'proprietaire']);
        $propietariosData = [];

        foreach ($propietarios as $propietario) {
            $documentos = $em->getRepository(Documento::class)->findBy(
                ['propietario' => $propietario],
                ['fechaSubida' => 'DESC']
            );
            $propietariosData[] = [
                'usuario' => $propietario,
                'documentos' => $documentos,
            ];
        }

        return $this->render('admin/documentos/index.html.twig', [
            'propietariosData' => $propietariosData,
        ]);
    }

    // Descarga segura
    #[Route('/documento/descargar/{id}', name: 'documento_descargar')]
    public function descargar(int $id, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $documento = $em->getRepository(Documento::class)->find($id);

        if (!$documento) {
            $this->addFlash('error', 'El documento solicitado no existe.');
            return $this->redirectToRoute('mis_documentos_index');
        }

        if ($documento->getPropietario() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('No tienes permiso para acceder a este archivo confidencial.');
        }

        $filePath = $this->getParameter('kernel.project_dir') . '/public/uploads/documentos/' . $documento->getNombreArchivo();

        if (!file_exists($filePath)) {
            $this->addFlash('error', 'El archivo físico no se encuentra en el servidor.');
            return $this->redirectToRoute('mis_documentos_index');
        }

        return $this->file($filePath, $documento->getNombreOriginal(), HeaderUtils::DISPOSITION_ATTACHMENT);
    }
}