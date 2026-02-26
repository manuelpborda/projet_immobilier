<?php

namespace App\Service;

use MongoDB\Client; // Cliente oficial MongoDB PHP

/**
 * Servicio para registrar mensajes de contacto en MongoDB.
 * Justificación ante jurado: integro NoSQL como bitácora ligera (auditoría),
 * sin modificar el flujo SQL existente; almaceno texto y fecha en formato ISO para fácil lectura.
 */
class MongoContactLogger
{
    private \MongoDB\Collection $collection;

    public function __construct(Client $client, string $dbName)
    {
        // Selecciono la colección "contact_messages" dentro de la base de datos MongoDB definida en .env
        $this->collection = $client->selectCollection($dbName, 'contact_messages');
    }

    /**
     * Inserta un documento con los datos enviados desde el formulario de contacto.
     *
     * @param array $data   Datos básicos del contacto (nombre, teléfono, correo, mensaje)
     * @param int|null $userId  ID del usuario autenticado (si aplica)
     */
    public function logContact(array $data, ?int $userId = null): void
    {
        // Documento NoSQL: datos básicos + fecha ISO
        $doc = [
            'nombre'    => (string)($data['nombre'] ?? ''),
            'telefono'  => (string)($data['telefono'] ?? ''),
            'correo'    => (string)($data['correo'] ?? ''),
            'mensaje'   => (string)($data['mensaje'] ?? ''),
            'createdAt' => (new \DateTimeImmutable())->format('c'), // Fecha legible ISO 8601
        ];

        // Si el usuario está autenticado, guardo también su ID
        if ($userId !== null) {
            $doc['userId'] = $userId;
        }

        // Inserto en MongoDB (colección contact_messages)
        $this->collection->insertOne($doc);
    }
}
