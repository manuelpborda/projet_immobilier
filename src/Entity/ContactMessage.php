<?php

namespace App\Entity;

use App\Repository\ContactMessageRepository; // Importa correctamente el repositorio
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactMessageRepository::class)]
class ContactMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 255)]
    private $name;

    #[ORM\Column(type: "string", length: 20, nullable: true)] // Corrijo tipo y longitud del teléfono
    private $phone;

    #[ORM\Column(type: "string", length: 255)]
    private $email;

    #[ORM\Column(type: "text")]
    private $message;

    #[ORM\Column(type: "datetime", options:["default"=>"CURRENT_TIMESTAMP"])] // Fecha de envío del mensaje
    private $fechaEnvio;
public function __construct()
{
    $this->fechaEnvio = new \DateTime();
}
    // === GETTERS y SETTERS ===

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getFechaEnvio(): ?\DateTimeInterface
    {
        return $this->fechaEnvio;
    }

    public function setFechaEnvio(\DateTimeInterface $fechaEnvio): self
    {
        $this->fechaEnvio = $fechaEnvio;
        return $this;
    }
}
