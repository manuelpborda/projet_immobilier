<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    /**
     * Este método muestra y procesa el formulario de registro.
     * Uso la ruta "/register" para que los usuarios puedan acceder fácilmente desde el menú principal.
     */
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Creo una nueva instancia de usuario
        $user = new User();

        // Construyo el formulario usando el formulario personalizado RegistrationFormType
        $form = $this->createForm(RegistrationFormType::class, $user);

        // Procesa la solicitud (POST o GET)
        $form->handleRequest($request);

        // Si el formulario fue enviado y es válido, procedo al registro
        if ($form->isSubmitted() && $form->isValid()) {
            // Hasheo la contraseña ingresada por el usuario, nunca guardo texto plano
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Obtengo el tipo de usuario seleccionado en el formulario (cliente, propietario, agente)
            $userType = $form->get('type')->getData();

            // Asigno roles automáticamente según el tipo de usuario elegido
            switch ($userType) {
                case 'agent':
                    $user->setRoles(['ROLE_ADMIN', 'ROLE_SUPER_ADMIN']);
                    break;
                case 'proprietaire':
                    $user->setRoles(['ROLE_PROPRIETAIRE']);
                    break;
                case 'client':
                default:
                    $user->setRoles(['ROLE_CLIENT']);
                    break;
            }

            // Persiste el usuario en la base de datos
            $entityManager->persist($user);
            $entityManager->flush();

            // Agrego un mensaje flash para informar éxito en el registro
            $this->addFlash('success', '¡Usuario registrado exitosamente! Ahora puedes iniciar sesión.');

            // Redirijo al login (puedes cambiar a otra ruta si lo prefieres)
            return $this->redirectToRoute('home');
        }

        // Renderizo la vista del formulario de registro
        return $this->render('registration/index.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
