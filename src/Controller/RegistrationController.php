<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route; // Actualizado a la sintaxis moderna de atributos

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        // CONTROL DE ACCESO: Si el usuario ya inició sesión, lo redirigimos de forma segura
        // al catálogo principal de la vitrina comercial ('home') en lugar de un ID fijo expuesto a un 404.
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        // Creamos el nuevo objeto User vacío que se llenará con los datos del formulario
        $user = new User();

        // Creo el formulario usando el tipo correspondiente
        $form = $this->createForm(RegistrationFormType::class, $user);

        // Symfony procesa automáticamente la petición HTTP y rellena el objeto $user
        $form->handleRequest($request);

        // Si el formulario fue enviado y pasa las validaciones...
        if ($form->isSubmitted() && $form->isValid()) {
            // Tomo la contraseña en texto plano que viene del formulario
            $plainPassword = $form->get('plainPassword')->getData();

            // Codifico esa contraseña de forma segura con el algoritmo criptográfico activo
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            // CORRECCIÓN CRÍTICA DE SEGURIDAD: Sincronización automática de Roles
            // Tomamos el 'typeUser' seleccionado y lo traducimos al formato estricto que exige Symfony.
            $tipoSeleccionado = $user->getTypeUser(); 
            
            if ($tipoSeleccionado === 'proprietaire') {
                $user->setRoles(['ROLE_PROPRIETAIRE']);
            } elseif ($tipoSeleccionado === 'admin') {
                $user->setRoles(['ROLE_ADMIN']);
            } else {
                // Rol de respaldo por seguridad para clientes o si el campo viene vacío
                $user->setRoles(['ROLE_CLIENT']);
            }

            // Guardo el nuevo usuario completamente configurado en la base de datos
            $entityManager->persist($user);
            $entityManager->flush();

            // Mensaje de confirmación localizador para el usuario
            $this->addFlash('success', '¡Registro exitoso! Por favor, inicia sesión con tus credenciales.');

            // Redirigimos siempre a la pantalla de login para que complete su autenticación de manera limpia
            return $this->redirectToRoute('app_login');
        }

        // Si aún no se ha enviado o contiene errores de validación, renderizamos la plantilla intacta
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}