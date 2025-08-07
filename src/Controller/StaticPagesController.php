<?php

namespace App\Controller;

use App\Entity\ContactMessage; // Para guardar los mensajes de contacto en la base de datos
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface; // Asegúrate de agregar esta línea para gestionar la persistencia en la BD

class StaticPagesController extends AbstractController
{
    // Ruta para la página de "Comprar" - Se muestra el listado de propiedades en venta.
    #[Route('/buy', name: 'buy')]
    public function buy(): Response
    {
        // Retorno la vista de la página de compra, usando la plantilla correspondiente.
        return $this->render('static/buy.html.twig');
    }

    // Ruta para la página de "Vender" - Se muestra el listado de propiedades para vender.
    #[Route('/sell', name: 'sell')]
    public function sell(): Response
    {
        // Retorno la vista de la página de venta, usando la plantilla correspondiente.
        return $this->render('static/sell.html.twig');
    }

    // Ruta para la página de "Arriendo" - Se muestra el listado de propiedades en arriendo.
    #[Route('/rent', name: 'rent')]
    public function rent(): Response
    {
        // Retorno la vista de la página de arriendo, usando la plantilla correspondiente.
        return $this->render('static/rent.html.twig');
    }

    // Ruta para la página de "Vacacional" - Se muestra el listado de propiedades vacacionales.
    #[Route('/vacation', name: 'vacation')]
    public function vacation(): Response
    {
        // Retorno la vista de la página vacacional, usando la plantilla correspondiente.
        return $this->render('static/vacation.html.twig');
    }

    // Ruta para la página "About" - Muestra información sobre la agencia.
    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        // Retorno la vista de la página "About", usando la plantilla correspondiente.
        return $this->render('static/about.html.twig');
    }

    // Ruta para la página de contacto - Muestra el formulario de contacto
    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        // Renderizo la página de contacto, mostrando el formulario de contacto para que el usuario pueda enviar un mensaje.
        return $this->render('static/contact.html.twig');
    }

    // Ruta para procesar el envío del formulario de contacto (método POST)
    #[Route('/contact/post', name: 'contact_post', methods: ['POST'])]
    public function contactPost(Request $request, MailerInterface $mailer, EntityManagerInterface $manager): Response
    {
        // Verifico si el formulario fue enviado con el método POST
        if ($request->isMethod('POST')) {
            // Capturo los datos enviados en el formulario
            $nombre = $request->request->get('nombre');
            $telefono = $request->request->get('telefono');
            $correo = $request->request->get('correo');
            $mensaje = $request->request->get('mensaje');

            // Aquí estoy creando un objeto de correo para enviarlo a la inmobiliaria (agente)
            $email = (new Email())
                ->from($correo) // El correo del usuario
                ->to('gabrielpulido1984@gmail.com') // Dirección del asesor inmobiliario
                ->subject('Nuevo mensaje de contacto de ' . $nombre) // Asunto del correo
                ->html('<p><strong>Nombre:</strong> ' . $nombre . '</p>
                        <p><strong>Teléfono:</strong> ' . $telefono . '</p>
                        <p><strong>Correo:</strong> ' . $correo . '</p>
                        <p><strong>Mensaje:</strong> ' . $mensaje . '</p>'); // Contenido del mensaje en formato HTML

            // Envío el correo (descomentado cuando se quiera enviar realmente el correo)
            $mailer->send($email);

            // Después de enviar el correo, guardo el mensaje en la base de datos
            $contactMessage = new ContactMessage();
            $contactMessage->setName($nombre);
            $contactMessage->setPhone($telefono);
            $contactMessage->setEmail($correo);
            $contactMessage->setMessage($mensaje);

            // Persisto el mensaje en la base de datos
            $manager->persist($contactMessage);
            $manager->flush();

            // Añado un mensaje flash para mostrar un mensaje de éxito en la interfaz
            $this->addFlash('success', '¡Gracias por contactarnos! Te responderemos lo más pronto posible.');

            // Después de enviar y guardar el mensaje, redirijo a la página de contacto
            return $this->redirectToRoute('contact');
        }

        // Si el método no es POST, simplemente retorno la página de contacto con el formulario
        return $this->render('static/contact.html.twig');
    }

    #[Route('/mentions-legales', name: 'mentions_legales')] // Ruta para la página de menciones legales
public function mentionsLegales(): Response
{
    return $this->render('mentions_legales.html.twig');
}

}
