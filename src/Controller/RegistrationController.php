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
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        // Aquí creo un nuevo objeto User vacío que se llenará con los datos del formulario
        $user = new User();

        // Creo el formulario usando el tipo que definimos antes
        $form = $this->createForm(RegistrationFormType::class, $user);

        // Symfony procesa automáticamente la petición HTTP y rellena el objeto $user si los datos son válidos
        $form->handleRequest($request);

        // Si el formulario fue enviado y es válido...
        if ($form->isSubmitted() && $form->isValid()) {
            // Tomo la contraseña en texto plano que viene del formulario
            $plainPassword = $form->get('plainPassword')->getData();

            // Codifico esa contraseña antes de guardarla
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            // Opcional: podría agregar manualmente roles si se quisiera.
            // En este caso lo manejamos con el campo `typeUser` que luego se transforma a roles automáticamente

            // Guardo el nuevo usuario en la base de datos
            $entityManager->persist($user);
            $entityManager->flush();

            // Opcional: mensaje de confirmación
            $this->addFlash('success', 'Usuario registrado exitosamente');

            // Redirijo según su tipo de usuario
            return $this->redirectToRoute('app_login'); // Puedo cambiar esto según los roles
        }

        // Si aún no se envió o hay errores, muestro el formulario
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
