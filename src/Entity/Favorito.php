<?php

namespace App\Entity;

use App\Repository\FavoritoRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use App\Entity\Bien;

#[ORM\Entity(repositoryClass: FavoritoRepository::class)]
class Favorito
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    // Muchos favoritos pueden pertenecer a un usuario
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    // Muchos favoritos pueden apuntar al mismo bien
    #[ORM\ManyToOne(targetEntity: Bien::class, inversedBy: 'favoritos')]
    #[ORM\JoinColumn(name: "bien_id", referencedColumnName: "id_bien", nullable: false)]
    private ?Bien $bien = null;

    public function getId(): ?int { return $this->id; }

    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): self { $this->user = $user; return $this; }

    public function getBien(): ?Bien { return $this->bien; }
    public function setBien(?Bien $bien): self { $this->bien = $bien; return $this; }
}
