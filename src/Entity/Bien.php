<?php

namespace App\Entity;

use App\Repository\BienRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use App\Entity\Visite;
use App\Entity\Offre;
use App\Entity\Favorito;

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

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "id_proprietaire", referencedColumnName: "id", nullable: true)]
    private ?User $proprietaire = null;

    #[ORM\OneToMany(targetEntity: Visite::class, mappedBy: 'bien')]
    private Collection $visites;

    #[ORM\OneToMany(targetEntity: Offre::class, mappedBy: 'bien')]
    private Collection $offres;

    #[ORM\OneToMany(targetEntity: Favorito::class, mappedBy: 'bien')]
    private Collection $favoritos;

    // CORRECCIÓN: Cambiado a string para soportar "Campestre"
    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private ?string $estrato = null;

    #[ORM\Column(type: "decimal", precision: 12, scale: 2, nullable: true)]
    private ?string $prixAdministration = null;

    #[ORM\Column(type: "json", nullable: true)]
    private array $caracteristiquesFlexibles = [];

    #[ORM\Column(length: 255)]
    private ?string $titulo = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $matricula = null;

    #[ORM\Column]
    private ?int $habitaciones = null;

    #[ORM\Column]
    private ?int $banos = null;

    // CORRECCIÓN: Cambiado explícitamente a string para soportar "Comunal" o "4+"
    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private ?string $garajes = null;

    #[ORM\Column(nullable: true)]
    private ?int $anoConstruccion = null;

    #[ORM\Column(nullable: true)]
    private ?int $numeroPiso = null;

    #[ORM\Column(nullable: true)]
    private ?float $areaConstruida = null;

    #[ORM\Column(length: 100)]
    private ?string $departamento = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $barrio = null;

    public function __construct()
    {
        $this->visites = new ArrayCollection();
        $this->offres = new ArrayCollection();
        $this->favoritos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeDeBien(): ?string
    {
        return $this->typeDeBien;
    }

    public function setTypeDeBien(?string $typeDeBien): self
    {
        $this->typeDeBien = $typeDeBien;
        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(?string $prix): self
    {
        $this->prix = $prix;
        return $this;
    }

    public function getSurfaceM2(): ?int
    {
        return $this->surfaceM2;
    }

    public function setSurfaceM2(?int $surfaceM2): self
    {
        $this->surfaceM2 = $surfaceM2;
        return $this;
    }

    public function getEtatDuBien(): ?string
    {
        return $this->etatDuBien;
    }

    public function setEtatDuBien(?string $etatDuBien): self
    {
        $this->etatDuBien = $etatDuBien;
        return $this;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function setFoto(?string $foto): self
    {
        $this->foto = $foto;
        return $this;
    }

    public function getTipoTransaccion(): ?string
    {
        return $this->tipoTransaccion;
    }

    public function setTipoTransaccion(?string $tipoTransaccion): self
    {
        $this->tipoTransaccion = $tipoTransaccion;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getProprietaire(): ?User
    {
        return $this->proprietaire;
    }

    public function setProprietaire(?User $proprietaire): self
    {
        $this->proprietaire = $proprietaire;
        return $this;
    }

    /**
     * @return Collection<int, Visite>
     */
    public function getVisites(): Collection
    {
        return $this->visites;
    }

    public function addVisite(Visite $visite): self
    {
        if (!$this->visites->contains($visite)) {
            $this->visites->add($visite);
            $visite->setBien($this);
        }
        return $this;
    }

    public function removeVisite(Visite $visite): self
    {
        if ($this->visites->removeElement($visite)) {
            if ($visite->getBien() === $this) {
                $visite->setBien(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Offre>
     */
    public function getOffres(): Collection
    {
        return $this->offres;
    }

    public function addOffre(Offre $offre): self
    {
        if (!$this->offres->contains($offre)) {
            $this->offres->add($offre);
            $offre->setBien($this);
        }
        return $this;
    }

    public function removeOffre(Offre $offre): self
    {
        if ($this->offres->removeElement($offre)) {
            if ($offre->getBien() === $this) {
                $offre->setBien(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Favorito>
     */
    public function getFavoritos(): Collection
    {
        return $this->favoritos;
    }

    public function addFavorito(Favorito $favorito): self
    {
        if (!$this->favoritos->contains($favorito)) {
            $this->favoritos->add($favorito);
            $favorito->setBien($this);
        }
        return $this;
    }

    public function removeFavorito(Favorito $favorito): self
    {
        if ($this->favoritos->removeElement($favorito)) {
            if ($favorito->getBien() === $this) {
                $favorito->setBien(null);
            }
        }
        return $this;
    }

    // CORRECCIÓN: Getter y Setter de Estrato a ?string
    public function getEstrato(): ?string
    {
        return $this->estrato;
    }

    public function setEstrato(?string $estrato): self
    {
        $this->estrato = $estrato;
        return $this;
    }

    public function getPrixAdministration(): ?string
    {
        return $this->prixAdministration;
    }

    public function setPrixAdministration(?string $prixAdministration): self
    {
        $this->prixAdministration = $prixAdministration;
        return $this;
    }

    public function getCaracteristiquesFlexibles(): array
    {
        return $this->caracteristiquesFlexibles;
    }

    public function setCaracteristiquesFlexibles(?array $caracteristiquesFlexibles): self
    {
        $this->caracteristiquesFlexibles = $caracteristiquesFlexibles ?? [];
        return $this;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;
        return $this;
    }

    public function getMatricula(): ?string
    {
        return $this->matricula;
    }

    public function setMatricula(?string $matricula): static
    {
        $this->matricula = $matricula;
        return $this;
    }

    public function getHabitaciones(): ?int
    {
        return $this->habitaciones;
    }

    public function setHabitaciones(int $habitaciones): static
    {
        $this->habitaciones = $habitaciones;
        return $this;
    }

    public function getBanos(): ?int
    {
        return $this->banos;
    }

    public function setBanos(int $banos): static
    {
        $this->banos = $banos;
        return $this;
    }

    // CORRECCIÓN: Getter y Setter de Garajes a ?string
    public function getGarajes(): ?string
    {
        return $this->garajes;
    }

    public function setGarajes(?string $garajes): static
    {
        $this->garajes = $garajes;
        return $this;
    }

    public function getAnoConstruccion(): ?int
    {
        return $this->anoConstruccion;
    }

    public function setAnoConstruccion(?int $anoConstruccion): static
    {
        $this->anoConstruccion = $anoConstruccion;
        return $this;
    }

    public function getNumeroPiso(): ?int
    {
        return $this->numeroPiso;
    }

    public function setNumeroPiso(?int $numeroPiso): static
    {
        $this->numeroPiso = $numeroPiso;
        return $this;
    }

    public function getAreaConstruida(): ?float
    {
        return $this->areaConstruida;
    }

    public function setAreaConstruida(?float $areaConstruida): static
    {
        $this->areaConstruida = $areaConstruida;
        return $this;
    }

    public function getDepartamento(): ?string
    {
        return $this->departamento;
    }

    public function setDepartamento(string $departamento): static
    {
        $this->departamento = $departamento;
        return $this;
    }

    public function getBarrio(): ?string
    {
        return $this->barrio;
    }

    public function setBarrio(?string $barrio): static
    {
        $this->barrio = $barrio;
        return $this;
    }
}