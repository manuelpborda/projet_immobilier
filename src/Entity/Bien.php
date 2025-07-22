<?php

namespace App\Entity;

use App\Repository\BienRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BienRepository::class)]
class Bien
{
    // --- Clave primaria ---
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_bien", type: "integer")]
    private ?int $id = null;

    // --- Tipo de bien (Apartamento, Casa, etc) ---
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $typeDeBien = null;

    // --- Ciudad donde se ubica ---
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $ville = null;

    // --- Dirección específica ---
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    // --- Precio del inmueble ---
    #[ORM\Column(type: "decimal", precision: 15, scale: 2, nullable: true)]
    private ?string $prix = null;

    // --- Área en metros cuadrados ---
    #[ORM\Column(nullable: true)]
    private ?int $surfaceM2 = null;

    // --- Estado del bien (Nuevo, Antiguo, etc) ---
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etatDuBien = null;

    // --- Relación con propietario (opcional) ---
    #[ORM\ManyToOne(inversedBy: 'biens')]
    #[ORM\JoinColumn(name: "id_proprietaire", referencedColumnName: "id_proprietaire", nullable: true)]
    private ?Proprietaire $proprietaire = null;

    // --- Relación con visitas ---
    #[ORM\OneToMany(targetEntity: Visite::class, mappedBy: 'bien')]
    private Collection $visites;

    // --- Relación con ofertas ---
    #[ORM\OneToMany(targetEntity: Offre::class, mappedBy: 'bien')]
    private Collection $offres;

    // --- NUEVO: Tipo de transacción (Venta, Arriendo, Alquiler Vacacional) ---
    #[ORM\Column(length: 30, nullable: true)]
    private ?string $tipoTransaccion = null;

    // --- Constructor ---
    public function __construct()
    {
        $this->visites = new ArrayCollection();
        $this->offres = new ArrayCollection();
    }

    // --- Getters y Setters ---
    public function getId(): ?int { return $this->id; }
    public function setId(int $id): static { $this->id = $id; return $this; }

    public function getTypeDeBien(): ?string { return $this->typeDeBien; }
    public function setTypeDeBien(?string $typeDeBien): static { $this->typeDeBien = $typeDeBien; return $this; }

    public function getVille(): ?string { return $this->ville; }
    public function setVille(?string $ville): static { $this->ville = $ville; return $this; }

    public function getAdresse(): ?string { return $this->adresse; }
    public function setAdresse(?string $adresse): static { $this->adresse = $adresse; return $this; }

    public function getPrix(): ?string { return $this->prix; }
    public function setPrix(?string $prix): static { $this->prix = $prix; return $this; }

    public function getSurfaceM2(): ?int { return $this->surfaceM2; }
    public function setSurfaceM2(?int $surfaceM2): static { $this->surfaceM2 = $surfaceM2; return $this; }

    public function getEtatDuBien(): ?string { return $this->etatDuBien; }
    public function setEtatDuBien(?string $etatDuBien): static { $this->etatDuBien = $etatDuBien; return $this; }

    // Getter y Setter para tipo de transacción
    public function getTipoTransaccion(): ?string { return $this->tipoTransaccion; }
    public function setTipoTransaccion(?string $tipoTransaccion): static
    {
        $this->tipoTransaccion = $tipoTransaccion;
        return $this;
    }

    public function getVisites(): Collection { return $this->visites; }
    public function addVisite(Visite $visite): static
    {
        if (!$this->visites->contains($visite)) {
            $this->visites->add($visite);
            $visite->setBien($this);
        }
        return $this;
    }
    public function removeVisite(Visite $visite): static
    {
        if ($this->visites->removeElement($visite)) {
            if ($visite->getBien() === $this) {
                $visite->setBien(null);
            }
        }
        return $this;
    }

    public function getProprietaire(): ?Proprietaire { return $this->proprietaire; }
    public function setProprietaire(?Proprietaire $proprietaire): static { $this->proprietaire = $proprietaire; return $this; }

    public function getOffres(): Collection { return $this->offres; }
    public function addOffre(Offre $offre): static
    {
        if (!$this->offres->contains($offre)) {
            $this->offres->add($offre);
            $offre->setBien($this);
        }
        return $this;
    }
    public function removeOffre(Offre $offre): static
    {
        if ($this->offres->removeElement($offre)) {
            if ($offre->getBien() === $this) {
                $offre->setBien(null);
            }
        }
        return $this;
    }
}
