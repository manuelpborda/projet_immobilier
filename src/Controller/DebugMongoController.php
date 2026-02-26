<?php

namespace App\Controller;

use App\Document\ContactLog;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class DebugMongoController extends AbstractController
{
    #[Route('/debug/mongo/write', name: 'debug_mongo_write', methods: ['GET'])]
    public function write(DocumentManager $dm): Response
    {
        // (Yo) Inserto un documento mínimo para validar escritura en MongoDB.
        $log = new ContactLog();
        $log->name      = 'Demo Debug';
        $log->phone     = '000';
        $log->email     = 'demo@example.com';
        $log->message   = 'Inserción de prueba desde /debug/mongo/write';
        $log->createdAt = new \DateTimeImmutable('now');

        $dm->persist($log);
        $dm->flush();

        return new Response('Write OK id=' . $log->id);
    }
}
