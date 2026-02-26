<?php

namespace App\Entity;

use App\Repository\BienRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use App\Entity\Visite;
use App\Entity\Offre;
use App\Entity\Favorito; // Importo la entidad Favorito correctamente

#[ORM\Entity(repositoryClass: BienRepository::class)]
class Bien
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_bien", type: "integer")]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $typeDeBien = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $ville = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(type: "decimal", precision: 15, scale: 2, nullable: true)]
    private ?string $prix = null;

    #[ORM\Column(nullable: true)]
    private ?int $surfaceM2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etatDuBien = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $foto = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $tipoTransaccion = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "id_proprietaire", referencedColumnName: "id", nullable: true)]
    private ?User $proprietaire = null;

    #[ORM\OneToMany(targetEntity: Visite::class, mappedBy: 'bien')]
    private Collection $visites;

    #[ORM\OneToMany(targetEntity: Offre::class, mappedBy: 'bien')]
    private Collection $offres;

    #[ORM\OneToMany(targetEntity: Favorito::class, mappedBy: 'bien')]
    private Collection $favoritos;

    // Constructor: inicializo las colecciones relacionadas
    public function __construct()
    {
        $this->visites = new ArrayCollection();
        $this->offres = new ArrayCollection();
        $this->favoritos = new ArrayCollection();
    }

    // --- Getters y setters principales ---

    public function getId(): ?int { return $this->id; }

    public function getTypeDeBien(): ?string { return $this->typeDeBien; }
    public function setTypeDeBien(?string $typeDeBien): static {
        $this->typeDeBien = $typeDeBien;
        return $this;
    }

    public function getVille(): ?string { return $this->ville; }
    public function setVille(?string $ville): static {
        $this->ville = $ville;
        return $this;
    }

    public function getAdresse(): ?string { return $this->adresse; }
    public function setAdresse(?string $adresse): static {
        $this->adresse = $adresse;
        return $this;
    }

    public function getPrix(): ?string { return $this->prix; }
    public function setPrix(?string $prix): static {
        $this->prix = $prix;
        return $this;
    }

    public function getSurfaceM2(): ?int { return $this->surfaceM2; }
    public function setSurfaceM2(?int $surfaceM2): static {
        $this->surfaceM2 = $surfaceM2;
        return $this;
    }

    public function getEtatDuBien(): ?string { return $this->etatDuBien; }
    public function setEtatDuBien(?string $etatDuBien): static {
        $this->etatDuBien = $etatDuBien;
        return $this;
    }

    // --- Getter y setter corregidos para foto ---
    public function getFoto(): ?string {
        return $this->foto;
    }
    public function setFoto(?string $foto): static {
        $this->foto = $foto;
        return $this;
    }

    public function getTipoTransaccion(): ?string { return $this->tipoTransaccion; }
    public function setTipoTransaccion(?string $tipoTransaccion): static {
        $this->tipoTransaccion = $tipoTransaccion;
        return $this;
    }

    public function getProprietaire(): ?User { return $this->proprietaire; }
    public function setProprietaire(?User $proprietaire): static {
        $this->proprietaire = $proprietaire;
        return $this;
    }

    // --- Relaciones con visitas ---
    public function getVisites(): Collection {
        return $this->visites;
    }
    public function addVisite(Visite $visite): static {
        if (!$this->visites->contains($visite)) {
            $this->visites[] = $visite;
            $visite->setBien($this);
        }
        return $this;
    }
    public function removeVisite(Visite $visite): static {
        if ($this->visites->removeElement($visite)) {
            if ($visite->getBien() === $this) {
                $visite->setBien(null);
            }
        }
        return $this;
    }

    // --- Relaciones con ofertas ---
    public function getOffres(): Collection {
        return $this->offres;
    }
    public function addOffre(Offre $offre): static {
        if (!$this->offres->contains($offre)) {
            $this->offres[] = $offre;
            $offre->setBien($this);
        }
        return $this;
    }
    public function removeOffre(Offre $offre): static {
        if ($this->offres->removeElement($offre)) {
            if ($offre->getBien() === $this) {
                $offre->setBien(null);
            }
        }
        return $this;
    }

    // --- Relaciones con favoritos ---
    public function getFavoritos(): Collection {
        return $this->favoritos;
    }
    public function addFavorito(Favorito $favorito): static {
        if (!$this->favoritos->contains($favorito)) {
            $this->favoritos[] = $favorito;
            $favorito->setBien($this);
        }
        return $this;
    }
    public function removeFavorito(Favorito $favorito): static {
        if ($this->favoritos->removeElement($favorito)) {
            if ($favorito->getBien() === $this) {
                $favorito->setBien(null);
            }
        }
        return $this;
    }
}
