<?php

namespace App\Entity;

use App\Repository\OffreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OffreRepository::class)]
class Offre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_offre", type: "integer")]
    private ?int $idOffre = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $prix = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateOffre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etatNegociation = null;

    #[ORM\ManyToOne(inversedBy: 'offres')]
    #[ORM\JoinColumn(name: "id_client", referencedColumnName: "id_client", nullable: true)]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'offres')]
    #[ORM\JoinColumn(name: "id_bien", referencedColumnName: "id_bien", nullable: true)]
    private ?Bien $bien = null;

    public function getIdOffre(): ?int
    {
        return $this->idOffre;
    }

    public function setIdOffre(int $idOffre): static
    {
        $this->idOffre = $idOffre;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(?string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDateOffre(): ?\DateTimeInterface
    {
        return $this->dateOffre;
    }

    public function setDateOffre(?\DateTimeInterface $dateOffre): static
    {
        $this->dateOffre = $dateOffre;

        return $this;
    }

    public function getEtatNegociation(): ?string
    {
        return $this->etatNegociation;
    }

    public function setEtatNegociation(?string $etatNegociation): static
    {
        $this->etatNegociation = $etatNegociation;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getBien(): ?Bien
    {
        return $this->bien;
    }

    public function setBien(?Bien $bien): static
    {
        $this->bien = $bien;

        return $this;
    }
}
