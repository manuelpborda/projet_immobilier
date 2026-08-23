<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as Mapping;

#[Mapping\Entity]
#[Mapping\Table(name: 'documentos_propietarios')]
class Documento
{
    #[Mapping\Id]
    #[Mapping\GeneratedValue]
    #[Mapping\Column(type: 'integer')]
    private ?int $id = null;

    #[Mapping\Column(type: 'string', length: 255)]
    private ?string $nombreOriginal = null;

    #[Mapping\Column(type: 'string', length: 255)]
    private ?string $nombreArchivo = null; // Nombre único slugificado en el disco

    #[Mapping\Column(type: 'string', length: 50)]
    private ?string $tipoDocumento = null; // 'Contrato', 'Factura', 'Escritura', 'Otro'

    #[Mapping\Column(type: 'datetime')]
    private ?\DateTimeInterface $fechaSubida = null;

    // Relación con el Propietario (Usuario)
    #[Mapping\ManyToOne(targetEntity: User::class)]
    #[Mapping\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $propietario = null;

    public function __construct()
    {
        $this->fechaSubida = new \DateTime();
    }

    // Getters y Setters
    public function getId(): ?int { return $this->id; }

    public function getNombreOriginal(): ?string { return $this->nombreOriginal; }
    public function setNombreOriginal(string $nombreOriginal): self { $this->nombreOriginal = $nombreOriginal; return $this; }

    public function getNombreArchivo(): ?string { return $this->nombreArchivo; }
    public function setNombreArchivo(string $nombreArchivo): self { $this->nombreArchivo = $nombreArchivo; return $this; }

    public function getTipoDocumento(): ?string { return $this->tipoDocumento; }
    public function setTipoDocumento(string $tipoDocumento): self { $this->tipoDocumento = $tipoDocumento; return $this; }

    public function getFechaSubida(): ?\DateTimeInterface { return $this->fechaSubida; }
    public function setFechaSubida(\DateTimeInterface $fechaSubida): self { $this->fechaSubida = $fechaSubida; return $this; }

    public function getPropietario(): ?User { return $this->propietario; }
    public function setPropietario(?User $propietario): self { $this->propietario = $propietario; return $this; }
}