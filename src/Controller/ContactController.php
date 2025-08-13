<?php

namespace App\Controller;

use App\Document\ContactLog;
use Doctrine\ODM\MongoDB\DocumentManager as MongoDM;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;

final class ContactController extends AbstractController
{
    // (Yo) Unifico GET+POST en /contact para evitar rutas viejas.
    #[Route('/contact', name: 'contact', methods: ['GET', 'POST'])]
    public function contact(
        Request $request,
        MongoDM $dm,
        CsrfTokenManagerInterface $csrf,
        LoggerInterface $logger
    ): Response {
        if ($request->isMethod('GET')) {
            return $this->render('static/contact.html.twig', ['__stamp' => 'static/contact.html.twig']);
        }

        $logger->info('[CONTACT_POST] request received', [
            'uri'   => (string) $request->getRequestUri(),
            'route' => (string) $request->attributes->get('_route'),
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

        // (Yo) Acepto nombres en ES o EN por si el template cambia.
        $name    = trim((string) ($request->request->get('nombre')   ?? $request->request->get('name')    ?? ''));
        $phone   = trim((string) ($request->request->get('telefono') ?? $request->request->get('phone')   ?? ''));
        $email   = trim((string) ($request->request->get('correo')   ?? $request->request->get('email')   ?? ''));
        $message = trim((string) ($request->request->get('mensaje')  ?? $request->request->get('message') ?? ''));

        $logger->info('[CONTACT_POST] payload', compact('name','phone','email','message'));

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

        try {
            $log = new ContactLog();
            $log->name      = $name;
            $log->phone     = $phone !== '' ? $phone : null;
            $log->email     = $email;
            $log->message   = $message;
            $log->createdAt = new \DateTimeImmutable('now');

            $dm->persist($log);
            $dm->flush();

            $logger->info('[CONTACT_POST] Mongo saved', ['id' => $log->id]);
            $this->addFlash('success', 'Mensaje enviado, le contactaremos proximamente.');
        } catch (\Throwable $e) {
            $logger->error('[CONTACT_POST] Mongo error', ['ex' => $e->getMessage()]);
            $this->addFlash('error', 'MongoDB: error -> ' . $e->getMessage());
        }

        return $this->redirectToRoute('contact');
    }
}
