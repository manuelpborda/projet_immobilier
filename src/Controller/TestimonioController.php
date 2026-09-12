<?php

namespace App\Controller;

use App\Entity\Testimonio;
use App\Form\TestimonioForm;
use App\Repository\TestimonioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/testimonio')]
class TestimonioController extends AbstractController
{
    #[Route('', name: 'app_testimonio_index', methods: ['GET'])]
    public function index(TestimonioRepository $testimonioRepository): Response
    {
        // SEGURIDAD: Admin ve todos, los demás solo ven los suyos
        if ($this->isGranted('ROLE_ADMIN')) {
            $testimonios = $testimonioRepository->findAll();
        } else {
            // Filtramos usando el nuevo campo "usuario" que creamos
            $testimonios = $testimonioRepository->findBy(['usuario' => $this->getUser()]);
        }

        return $this->render('testimonio/index.html.twig', [
            'testimonios' => $testimonios,
        ]);
    }

    #[Route('/nuevo', name: 'app_testimonio_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $testimonio = new Testimonio();
        $form = $this->createForm(TestimonioForm::class, $testimonio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // MAGIA: Antes de guardar, le asignamos como dueño al usuario que está logueado
            $testimonio->setUsuario($this->getUser());
            
            $entityManager->persist($testimonio);
            $entityManager->flush();

            $this->addFlash('success', '¡Testimonio registrado con éxito!');
            return $this->redirectToRoute('app_testimonio_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('testimonio/new.html.twig', [
            'testimonio' => $testimonio,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_testimonio_show', methods: ['GET'])]
    public function show(Testimonio $testimonio): Response
    {
        $this->checkAccess($testimonio); // Revisar permisos antes de mostrar

        return $this->render('testimonio/show.html.twig', [
            'testimonio' => $testimonio,
        ]);
    }

    #[Route('/{id}/editar', name: 'app_testimonio_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Testimonio $testimonio, EntityManagerInterface $entityManager): Response
    {
        $this->checkAccess($testimonio); // Revisar permisos antes de editar

        $form = $this->createForm(TestimonioForm::class, $testimonio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Testimonio actualizado correctamente.');
            return $this->redirectToRoute('app_testimonio_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('testimonio/edit.html.twig', [
            'testimonio' => $testimonio,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_testimonio_delete', methods: ['POST'])]
    public function delete(Request $request, Testimonio $testimonio, EntityManagerInterface $entityManager): Response
    {
        $this->checkAccess($testimonio); // Revisar permisos antes de borrar

        if ($this->isCsrfTokenValid('delete'.$testimonio->getId(), $request->request->get('_token'))) {
            $entityManager->remove($testimonio);
            $entityManager->flush();
            $this->addFlash('success', 'Testimonio eliminado.');
        }

        return $this->redirectToRoute('app_testimonio_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Función privada: Escudo de seguridad centralizado
     */
    private function checkAccess(Testimonio $testimonio): void
    {
        // Si el usuario del testimonio no es el actual Y TAMPOCO es administrador... Bloquear.
        if ($testimonio->getUsuario() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException('Acceso denegado: No tienes permiso para acceder a este testimonio.');
        }
    }
}