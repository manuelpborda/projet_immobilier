<?php

namespace App\Controller;

use App\Document\ContactLog;                                // Documento ODM para MongoDB
use App\Entity\ContactMessage;                              // Entidad ORM para MySQL
use Doctrine\ODM\MongoDB\DocumentManager as MongoDM;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;                // Para guardar el modo en cookie
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;

final class ContactController extends AbstractController
{
    /**
     * Centralizo GET y POST en /contact:
     *  - En GET muestro el formulario y paso el modo activo a la vista.
     *  - En POST valido, recojo datos (acepto nombres ES/EN) y guardo según el modo: mongo | mysql | both.
     * Leo el modo en este orden: cookie -> query (?storage=) -> .env (CONTACT_STORAGE).
     */
    #[Route('/contact', name: 'contact', methods: ['GET', 'POST'])]
    public function contact(
        Request $request,
        MongoDM $dm,
        CsrfTokenManagerInterface $csrf,
        LoggerInterface $logger,
        EntityManagerInterface $em = null
    ): Response {
        // Modo activo (mongo | mysql | both). Por defecto dejo mongo.
        $default = $_ENV['CONTACT_STORAGE'] ?? $_SERVER['CONTACT_STORAGE'] ?? 'mongo';
        $mode = $request->cookies->get('contact_storage', $request->query->get('storage', $default));
        $mode = in_array($mode, ['mongo','mysql','both'], true) ? $mode : 'mongo';

        // GET: renderizo el formulario (y muestro un badge discreto con el modo).
        if ($request->isMethod('GET')) {
            return $this->render('static/contact.html.twig', [
                '__stamp'     => 'static/contact.html.twig',
                'storageMode' => $mode,
            ]);
        }

        // POST: trazo y valido CSRF (puedo desactivarlo en pruebas con CONTACT_SKIP_CSRF=1 en .env).
        $logger->info('[CONTACT_POST] request received', [
            'uri'   => (string) $request->getRequestUri(),
            'route' => (string) $request->attributes->get('_route'),
            'mode'  => $mode,
        ]);

        $skipCsrf = ($_ENV['CONTACT_SKIP_CSRF'] ?? $_SERVER['CONTACT_SKIP_CSRF'] ?? '0') === '1';
        $tokenVal = (string) $request->request->get('_csrf_token');

        if (!$skipCsrf && !$csrf->isTokenValid(new CsrfToken('contact_form', $tokenVal))) {
            $this->addFlash('error', 'Token CSRF inválido');
            $logger->warning('[CONTACT_POST] invalid CSRF');
            return $this->redirectToRoute('contact');
        } elseif ($skipCsrf) {
            $logger->warning('[CONTACT_POST] CSRF skipped by env flag');
        }

        // Recojo campos permitiendo nombres en español o inglés para evitar desajustes de plantilla.
        $name    = trim((string) ($request->request->get('nombre')   ?? $request->request->get('name')    ?? ''));
        $phone   = trim((string) ($request->request->get('telefono') ?? $request->request->get('phone')   ?? ''));
        $email   = trim((string) ($request->request->get('correo')   ?? $request->request->get('email')   ?? ''));
        $message = trim((string) ($request->request->get('mensaje')  ?? $request->request->get('message') ?? ''));

        $logger->info('[CONTACT_POST] payload', compact('name','phone','email','message'));

        // Validación mínima para una demo sólida.
        if ($name === '' || $email === '' || $message === '') {
            $this->addFlash('error', 'Por favor completa nombre, correo y mensaje.');
            $logger->warning('[CONTACT_POST] validation failed: required fields empty');
            return $this->redirectToRoute('contact');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addFlash('error', 'El correo no es válido.');
            $logger->warning('[CONTACT_POST] validation failed: email invalid');
            return $this->redirectToRoute('contact');
        }

        // Si el modo incluye MySQL (mysql|both), persisto la entidad ContactMessage y uso setFechaEnvio(...).
        if (in_array($mode, ['mysql','both'], true) && $em !== null) {
            $contact = new ContactMessage();
            $contact->setName($name);
            $contact->setPhone($phone !== '' ? $phone : null);
            $contact->setEmail($email);
            $contact->setMessage($message);
            $contact->setFechaEnvio(new \DateTimeImmutable('now'));   // <- ajuste a tu entidad
            $em->persist($contact);
            $em->flush();
        }

        // Si el modo incluye Mongo (mongo|both), guardo en la colección contact_messages.
        if (in_array($mode, ['mongo','both'], true)) {
            try {
                $log = new ContactLog();
                $log->name      = $name;
                $log->phone     = $phone !== '' ? $phone : null;
                $log->email     = $email;
                $log->message   = $message;
                $log->createdAt = new \DateTimeImmutable('now');

                $dm->persist($log);
                $dm->flush();
            } catch (\Throwable $e) {
                $logger->error('[CONTACT_POST] Mongo error', ['ex' => $e->getMessage()]);
                $this->addFlash('error', 'MongoDB: error -> ' . $e->getMessage());
                return $this->redirectToRoute('contact');
            }
        }

        // Mensaje de éxito y redirección.
        $this->addFlash('success', 'Mensaje enviado, le contactaremos próximamente.');
        return $this->redirectToRoute('contact');
    }

    /**
     * Palanca discreta: ajusto el modo de almacenamiento en una cookie y vuelvo al formulario.
     * La vista muestra solo un badge pequeño; 
     */
    #[Route('/mode/{mode}', name: 'contact_mode', requirements: ['mode' => 'mongo|mysql|both'], methods: ['GET'])]
    public function setMode(string $mode): Response
    {
        $resp = $this->redirectToRoute('contact');
        // Cookie válida 1 día, en toda la app.
        $resp->headers->setCookie(new Cookie('contact_storage', $mode, strtotime('+1 day'), '/'));
        return $resp;
    }
}
