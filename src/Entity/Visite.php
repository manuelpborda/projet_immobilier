<?php

namespace App\Entity;

use App\Repository\VisiteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use App\Entity\Bien;

#[ORM\Entity(repositoryClass: VisiteRepository::class)]
class Visite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_visites", type: "integer")]
    private ?int $idVisites = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateVisite = null;

    // Agente como User con typeUser = 'admin' o 'agent'
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "id_agent", referencedColumnName: "id", nullable: true)]
    private ?User $agent = null;

    // Cliente como User con typeUser = 'client'
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "id_client", referencedColumnName: "id", nullable: true)]
    private ?User $client = null;

    #[ORM\ManyToOne(targetEntity: Bien::class, inversedBy: 'visites')]
    #[ORM\JoinColumn(name: "id_bien", referencedColumnName: "id_bien", nullable: true)]
    private ?Bien $bien = null;

    public function getIdVisites(): ?int { return $this->idVisites; }
    public function setIdVisites(int $idVisites): static { $this->idVisites = $idVisites; return $this; }

    public function getDateVisite(): ?\DateTimeInterface { return $this->dateVisite; }
    public function setDateVisite(?\DateTimeInterface $dateVisite): static { $this->dateVisite = $dateVisite; return $this; }

    public function getAgent(): ?User { return $this->agent; }
    public function setAgent(?User $agent): static { $this->agent = $agent; return $this; }

    public function getClient(): ?User { return $this->client; }
    public function setClient(?User $client): static { $this->client = $client; return $this; }

    public function getBien(): ?Bien { return $this->bien; }
    public function setBien(?Bien $bien): static { $this->bien = $bien; return $this; }
}
