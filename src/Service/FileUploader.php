<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    public function __construct(
        private string $targetDirectory,
        private SluggerInterface $slugger
    ) {}

    public function upload(UploadedFile $file): string
    {
        // Extraemos el nombre original sin la extensión
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        
        // Limpiamos el nombre (ej: "Casa de Campo" pasa a "casa-de-campo")
        $safeFilename = $this->slugger->slug($originalFilename);
        
        // Generamos un nombre único final conservando su extensión original
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            // Movemos el archivo físico al directorio destino configurado
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // Lanza una excepción si algo falla (ej: falta de permisos en el servidor)
            throw new \Exception('No se pudo guardar la imagen del inmueble.');
        }

        return $fileName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}