<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Ya existe una cuenta con este email.')] // <-- Aquí pongo la validación
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // ID autoincremental que utilizo como clave primaria de cada usuario
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    // El correo electrónico es el identificador único para el login
    #[ORM\Column(type: "string", length: 180, unique: true)]
    private $email;

    // El campo roles me permite distinguir los permisos y acceso de cada usuario
    #[ORM\Column(type: "json")]
    private $roles = [];

    // Aquí almaceno la contraseña del usuario, siempre hasheada para seguridad
    #[ORM\Column(type: "string")]
    private $password;

    // Este campo personalizado lo agregué para distinguir el tipo de usuario (cliente, propietario o agente)
    #[ORM\Column(type: "string", length: 20)]
    private $type;

    /**
     * @var Collection<int, Favorito>
     */
    #[ORM\OneToMany(targetEntity: Favorito::class, mappedBy: 'user')]
    private Collection $bien;

    public function __construct()
    {
        $this->bien = new ArrayCollection();
    } // Valores: 'client', 'proprietaire', 'agent'

    // Si deseo agregar más información (nombre, teléfono, etc.) puedo añadir más atributos aquí

    // Getter del ID: necesario para la gestión interna y relaciones en Doctrine
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter y setter del email, usado como identificador único
    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    // Método estándar de Symfony 5+ para identificar al usuario por su email
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
    // Método legado para compatibilidad
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    // Devuelvo siempre al menos un rol por defecto (ROLE_USER) si el array está vacío
    public function getRoles(): array
    {
        $roles = $this->roles;
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }
        return array_unique($roles);
    }

    // Setter de roles: puedo asignar múltiples roles a cada usuario
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    // Devuelve la contraseña hasheada, requerida para la autenticación
    public function getPassword(): string
    {
        return $this->password;
    }

    // Setter de contraseña, importante siempre almacenar el hash y no el texto plano
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    // Getter y setter para el campo 'type', que me permite saber si el usuario es cliente, propietario o agente
    public function getType(): ?string
    {
        return $this->type;
    }
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    // Método que me permite borrar información sensible después del login si fuese necesario
    public function eraseCredentials():void
    {
        // Aquí podría limpiar el campo plainPassword si lo uso temporalmente en el registro
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Favorito>
     */
    public function getBien(): Collection
    {
        return $this->bien;
    }

    public function addBien(Favorito $bien): static
    {
        if (!$this->bien->contains($bien)) {
            $this->bien->add($bien);
            $bien->setUser($this);
        }

        return $this;
    }

    public function removeBien(Favorito $bien): static
    {
        if ($this->bien->removeElement($bien)) {
            // set the owning side to null (unless already changed)
            if ($bien->getUser() === $this) {
                $bien->setUser(null);
            }
        }

        return $this;
    }
}
