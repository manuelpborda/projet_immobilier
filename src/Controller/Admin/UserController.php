<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/usuarios')]
final class UserController extends AbstractController
{
    #[Route('/', name: 'admin_user_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Capturar el término de búsqueda si existe
        $search = $request->query->get('q');
        
        $queryBuilder = $em->getRepository(User::class)->createQueryBuilder('u');

        // Lógica del buscador inteligente
        if ($search) {
            $queryBuilder->where('u.email LIKE :search OR u.firstName LIKE :search OR u.lastName LIKE :search OR u.phone LIKE :search')
                         ->setParameter('search', '%' . $search . '%');
        }

        $queryBuilder->orderBy('u.id', 'DESC');
        $usuarios = $queryBuilder->getQuery()->getResult();

        return $this->render('admin/user/index.html.twig', [
            'usuarios' => $usuarios,
            'search' => $search
        ]);
    }
    #[Route('/{id}/editar', name: 'admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(\App\Form\UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Mantiene sincronizado el array de roles con la elección del admin
            $rolFormat = $user->getTypeUser() === 'admin' ? 'ROLE_ADMIN' : 
                        ($user->getTypeUser() === 'client' ? 'ROLE_CLIENT' : 'ROLE_PROPRIETAIRE');
            
            $user->setRoles([$rolFormat]);
            $em->flush();
            
            $this->addFlash('success', 'Usuario actualizado correctamente.');
            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user/edit.html.twig', [
            'usuario' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/eliminar', name: 'admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Seguro anti-suicidio digital: El admin no puede borrarse a sí mismo
        if ($user === $this->getUser()) {
            $this->addFlash('error', 'Por seguridad, no puedes eliminar tu propia cuenta de Administrador.');
            return $this->redirectToRoute('admin_user_index');
        }

        // Validación del token de seguridad CSRF
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', 'El usuario ha sido eliminado correctamente del sistema.');
        }

        return $this->redirectToRoute('admin_user_index');
    }
}