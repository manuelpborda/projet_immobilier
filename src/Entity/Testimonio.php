<?php

namespace App\Entity;

use App\Repository\TestimonioRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
// IMPORTANTE: Asegúrate de que esta línea esté, es la que llama a tu entidad User
use App\Entity\User; 

#[ORM\Entity(repositoryClass: TestimonioRepository::class)]
class Testimonio
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombreCliente = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rolCliente = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $mensaje = null;

    #[ORM\Column]
    private ?int $calificacion = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $urlVideo = null;

    #[ORM\Column]
    private ?bool $activo = null;

    // === AQUÍ ESTÁ LA NUEVA RELACIÓN ===
    // Lo ponemos nullable: true por si ya tienes testimonios guardados de antes
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $usuario = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreCliente(): ?string
    {
        return $this->nombreCliente;
    }

    public function setNombreCliente(string $nombreCliente): static
    {
        $this->nombreCliente = $nombreCliente;

        return $this;
    }

    public function getRolCliente(): ?string
    {
        return $this->rolCliente;
    }

    public function setRolCliente(?string $rolCliente): static
    {
        $this->rolCliente = $rolCliente;

        return $this;
    }

    public function getMensaje(): ?string
    {
        return $this->mensaje;
    }

    public function setMensaje(?string $mensaje): static
    {
        $this->mensaje = $mensaje;

        return $this;
    }

    public function getCalificacion(): ?int
    {
        return $this->calificacion;
    }

    public function setCalificacion(int $calificacion): static
    {
        $this->calificacion = $calificacion;

        return $this;
    }

    public function getUrlVideo(): ?string
    {
        return $this->urlVideo;
    }

    public function setUrlVideo(?string $urlVideo): static
    {
        $this->urlVideo = $urlVideo;

        return $this;
    }

    public function isActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(bool $activo): static
    {
        $this->activo = $activo;

        return $this;
    }

    // === NUEVOS GETTERS Y SETTERS PARA EL USUARIO ===
    public function getUsuario(): ?User
    {
        return $this->usuario;
    }

    public function setUsuario(?User $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }
}