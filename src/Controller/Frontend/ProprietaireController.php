<?php

namespace App\Controller\Frontend;

use App\Entity\Bien;
use App\Entity\Documento;
use App\Form\BienType;
use App\Repository\BienRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Panel de control privado y gestión de inventario para Propietarios.
 * Blindamos toda la clase para asegurar que solo usuarios autorizados ejecuten estas acciones.
 */
#[Route('/proprietaire')]
#[IsGranted('ROLE_PROPRIETAIRE')]
class ProprietaireController extends AbstractController
{
    /**
     * Vista principal del panel: Lista únicamente los inmuebles del propietario actual.
     */
    #[Route('/dashboard', name: 'proprietaire_dashboard', methods: ['GET'])]
    public function dashboard(BienRepository $bienRepository): Response
    {
        $misBienes = $bienRepository->findBy(
            ['proprietaire' => $this->getUser()],
            ['id' => 'DESC']
        );

        return $this->render('proprietaire/dashboard.html.twig', [
            'user' => $this->getUser(),
            'bienes' => $misBienes,
        ]);
    }

    /**
     * Acción para publicar un nuevo inmueble con gestión de hasta 20 archivos (Imágenes/Videos).
     */
    #[Route('/bien/nuevo', name: 'proprietaire_bien_nuevo', methods: ['GET', 'POST'])]
    public function nuevo(Request $request, EntityManagerInterface $em, FileUploader $fileUploader): Response
    {
        $bien = new Bien();
        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fotosGuardadas = [];

            // Iteramos sobre los 20 casilleros posibles
            for ($i = 1; $i <= 20; $i++) {
                /** @var UploadedFile|null $fotoFile */
                $fotoFile = $form->get('foto' . $i)->getData();

                if ($fotoFile) {
                    try {
                        // Toda la lógica pesada se delega a nuestro servicio Senior
                        $newFilename = $fileUploader->upload($fotoFile);
                        $fotosGuardadas[] = $newFilename;
                    } catch (\Exception $e) {
                        $this->addFlash('error', 'Ocurrió un error al subir el archivo ' . $i);
                        return $this->redirectToRoute('proprietaire_bien_nuevo');
                    }
                }
            }

            // Unimos todos los nombres de archivo por comas para guardarlos
            if (!empty($fotosGuardadas)) {
                $bien->setFoto(implode(',', $fotosGuardadas));
            }

            $bien->setProprietaire($this->getUser());

            $em->persist($bien);
            $em->flush();

            $this->addFlash('success', '¡Excelente! El inmueble ha sido publicado exitosamente.');
            return $this->redirectToRoute('proprietaire_dashboard');
        }

        return $this->render('proprietaire/nuevo.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Acción para editar un inmueble existente con recolección automática de basura (unlink).
     */
    #[Route('/bien/editar/{id}', name: 'proprietaire_bien_editar', methods: ['GET', 'POST'])]
    public function editar(Bien $bien, Request $request, EntityManagerInterface $em, FileUploader $fileUploader): Response
    {
        if ($bien->getProprietaire() !== $this->getUser()) {
            throw $this->createAccessDeniedException('No tienes permisos para modificar este inmueble.');
        }

        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Extraemos los archivos actuales separándolos por coma
            $fotosActuales = $bien->getFoto() ? explode(',', $bien->getFoto()) : [];
            
            // Rellenamos el array para garantizar que tenga 20 posiciones (índices del 0 al 19)
            $fotosFinales = array_pad($fotosActuales, 20, null);

            // Capturamos las fotos que el usuario marcó para eliminar desde el diseño
            $fotosAEliminar = $request->request->all('eliminar_fotos') ?? [];
            
            // Obtenemos el directorio absoluto configurado en parameters
            $targetDir = $this->getParameter('bienes_images_directory');

            for ($i = 1; $i <= 20; $i++) {
                $slotIndex = $i - 1;
                $fotoVieja = $fotosFinales[$slotIndex];

                // CASO A: El usuario marcó explícitamente "Eliminar foto" desde el switch visual
                if (in_array($slotIndex, $fotosAEliminar)) {
                    $fotosFinales[$slotIndex] = null;
                    
                    if ($fotoVieja) {
                        $rutaFisica = $targetDir . '/' . $fotoVieja;
                        if (file_exists($rutaFisica)) {
                            unlink($rutaFisica); // Eliminación física del archivo viejo
                        }
                    }
                }

                /** @var UploadedFile|null $fotoFile */
                $fotoFile = $form->get('foto' . $i)->getData();

                if ($fotoFile) {
                    try {
                        // CASO B: Si el usuario sube un archivo nuevo en un slot que ya contenía una foto,
                        // destruimos la anterior para evitar almacenar archivos basura flotando en el disco.
                        if ($fotoVieja && $fotosFinales[$slotIndex] !== null) {
                            $rutaFisica = $targetDir . '/' . $fotoVieja;
                            if (file_exists($rutaFisica)) {
                                unlink($rutaFisica);
                            }
                        }

                        // Subimos el nuevo archivo multimedia
                        $newFilename = $fileUploader->upload($fotoFile);
                        
                        // Reemplazamos únicamente el slot correspondiente (índice i-1)
                        $fotosFinales[$slotIndex] = $newFilename;
                    } catch (\Exception $e) {
                        $this->addFlash('error', 'Ocurrió un error al actualizar el archivo ' . $i);
                        return $this->redirectToRoute('proprietaire_bien_editar', ['id' => $bien->getId()]);
                    }
                }
            }

            // Eliminamos valores nulos o vacíos del array final para que no queden huecos
            $fotosFiltradas = array_filter($fotosFinales, function ($val) {
                return !is_null($val) && $val !== '';
            });

            // Guardamos el string definitivo unido por comas
            $bien->setFoto(!empty($fotosFiltradas) ? implode(',', $fotosFiltradas) : null);

            $em->flush();

            $this->addFlash('success', 'Los datos del inmueble han sido actualizados correctamente y los archivos optimizados.');
            return $this->redirectToRoute('proprietaire_dashboard');
        }

        return $this->render('proprietaire/editar.html.twig', [
            'bien' => $bien,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Acción segura para dar de baja o eliminar una publicación y limpiar sus archivos físicos del disco.
     */
    #[Route('/bien/eliminar/{id}', name: 'proprietaire_bien_eliminar', methods: ['POST'])]
    public function eliminar(Bien $bien, Request $request, EntityManagerInterface $em): Response
    {
        if ($bien->getProprietaire() !== $this->getUser()) {
            throw $this->createAccessDeniedException('No tienes permisos para eliminar este inmueble.');
        }

        if ($this->isCsrfTokenValid('delete'.$bien->getId(), $request->request->get('_token'))) {
            
            // RECOLECCIÓN DE BASURA COMPLETA: Barremos todas las fotos asociadas a la propiedad
            if ($bien->getFoto()) {
                $targetDir = $this->getParameter('bienes_images_directory');
                $fotos = explode(',', $bien->getFoto());

                foreach ($fotos as $foto) {
                    if (!empty($foto)) {
                        $rutaFisica = $targetDir . '/' . $foto;
                        if (file_exists($rutaFisica)) {
                            unlink($rutaFisica); // Borra físicamente del disco local / hosting
                        }
                    }
                }
            }

            $em->remove($bien);
            $em->flush();
            $this->addFlash('success', 'El inmueble y todos sus archivos asociados han sido eliminados permanentemente de la plataforma.');
        } else {
            $this->addFlash('error', 'Token de seguridad inválido. No se pudo eliminar.');
        }

        return $this->redirectToRoute('proprietaire_dashboard');
    }

    /**
     * Gestión de documentos privados desde el panel del propietario.
     */
    #[Route('/mis-documentos', name: 'proprietaire_documentos', methods: ['GET', 'POST'])]
    public function misDocumentos(Request $request, EntityManagerInterface $em, \Symfony\Component\String\Slugger\SluggerInterface $slugger): Response
    {
        $documento = new Documento();
        $form = $this->createForm(\App\Form\DocumentoType::class, $documento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile|null $archivoFile */
            $archivoFile = $form->get('archivo')->getData();

            if ($archivoFile) {
                $originalFilename = pathinfo($archivoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $archivoFile->guessExtension();

                $directorioSeguro = $this->getParameter('propietarios_documentos_directory');

                // Aseguramos la creación de la carpeta protegida si no existe en var/
                if (!is_dir($directorioSeguro)) {
                    mkdir($directorioSeguro, 0777, true);
                }

                try {
                    $archivoFile->move($directorioSeguro, $newFilename);
                    
                    $documento->setNombreOriginal($archivoFile->getClientOriginalName());
                    $documento->setNombreArchivo($newFilename);
                    $documento->setPropietario($this->getUser());

                    $em->persist($documento);
                    $em->flush();

                    $this->addFlash('success', 'Documento confidencial compartido correctamente con la agencia.');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'No se pudo guardar el documento legal de forma segura.');
                }
            }

            return $this->redirectToRoute('proprietaire_documentos');
        }

        // Buscamos los documentos ya subidos por este propietario específico
        $misDocumentos = $em->getRepository(Documento::class)->findBy(
            ['propietario' => $this->getUser()],
            ['fechaSubida' => 'DESC']
        );

        return $this->render('proprietaire/documentos.html.twig', [
            'form' => $form->createView(),
            'documentos' => $misDocumentos
        ]);
    }

    /**
     * Eliminar un documento del panel privado.
     */
    #[Route('/documento/eliminar/{id}', name: 'proprietaire_documento_eliminar', methods: ['POST'])]
    public function eliminarDocumento(Documento $documento, Request $request, EntityManagerInterface $em): Response
    {
        if ($documento->getPropietario() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete_doc'.$documento->getId(), $request->request->get('_token'))) {
            // RECOLECCIÓN DE BASURA: Borramos el archivo físico del disco protegido
            $rutaArchivo = $this->getParameter('propietarios_documentos_directory') . '/' . $documento->getNombreArchivo();
            if (file_exists($rutaArchivo)) {
                unlink($rutaArchivo);
            }

            $em->remove($documento);
            $em->flush();

            $this->addFlash('success', 'El documento ha sido eliminado del registro.');
        }

        return $this->redirectToRoute('proprietaire_documentos');
    }
}