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

#[Route('/testimonio')]
class TestimonioController extends AbstractController
{
    #[Route('', name: 'app_testimonio_index', methods: ['GET'])]
    public function index(TestimonioRepository $testimonioRepository): Response
    {
        return $this->render('testimonio/index.html.twig', [
            'testimonios' => $testimonioRepository->findAll(),
        ]);
    }

    #[Route('/nuevo', name: 'app_testimonio_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $testimonio = new Testimonio();
        $form = $this->createForm(TestimonioForm::class, $testimonio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
        return $this->render('testimonio/show.html.twig', [
            'testimonio' => $testimonio,
        ]);
    }

    #[Route('/{id}/editar', name: 'app_testimonio_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Testimonio $testimonio, EntityManagerInterface $entityManager): Response
    {
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
        if ($this->isCsrfTokenValid('delete'.$testimonio->getId(), $request->request->get('_token'))) {
            $entityManager->remove($testimonio);
            $entityManager->flush();
            $this->addFlash('success', 'Testimonio eliminado.');
        }

        return $this->redirectToRoute('app_testimonio_index', [], Response::HTTP_SEE_OTHER);
    }
}