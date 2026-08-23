<?php

namespace App\Entity;

use App\Repository\EstudioClienteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EstudioClienteRepository::class)]
class EstudioCliente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $cedula = null;

    #[ORM\Column(length: 150)]
    private ?string $nombresApellidos = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $estadoCivil = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $genero = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $fechaCiudadNacimiento = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $fechaCiudadExpedicion = null;

    #[ORM\Column(length: 50)]
    private ?string $celular = null;

    #[ORM\Column(length: 100)]
    private ?string $correo = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $direccionDomicilio = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $direccionLaboral = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $ciudadTrabajoDomicilio = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nivelEstudios = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $profesion = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $empresaDondeLabora = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $cargo = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $tipoContrato = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $fechaIngreso = null;

    #[ORM\Column(nullable: true)]
    private ?int $personasCargo = null;

    #[ORM\Column(nullable: true)]
    private ?float $ingresosFijos = null;

    #[ORM\Column(nullable: true)]
    private ?float $ingresosVariables = null;

    #[ORM\Column(nullable: true)]
    private ?float $patrimonioInmuebles = null;

    #[ORM\Column(nullable: true)]
    private ?float $valorVehiculos = null;

    #[ORM\Column(nullable: true)]
    private ?float $deudasFinancieras = null;

    #[ORM\Column(nullable: true)]
    private ?float $gastosFinancieros = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descripcionPerfil = null;

    #[ORM\Column(nullable: true)]
    private ?float $valorInmueble = null;

    #[ORM\Column(nullable: true)]
    private ?float $valorSolicitado = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $sistemaAmortizacion = null;

    #[ORM\Column(nullable: true)]
    private ?int $plazoAnos = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $lineaCredito = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $tipoVivienda = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $ciudadInmueble = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $referenciaFamiliar = null;

    #[ORM\Column(length: 200, nullable: true)]
    private ?string $referenciaPersonal = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $aceptaTratamientoDatos = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaSolicitud = null;

    public function __construct()
    {
        $this->fechaSolicitud = new \DateTime();
    }

    // --- TODOS LOS GETTERS Y SETTERS EXPLÍCITOS ---

    public function getId(): ?int { return $this->id; }

    public function getCedula(): ?string { return $this->cedula; }
    public function setCedula(string $cedula): static { $this->cedula = $cedula; return $this; }

    public function getNombresApellidos(): ?string { return $this->nombresApellidos; }
    public function setNombresApellidos(string $nombresApellidos): static { $this->nombresApellidos = $nombresApellidos; return $this; }

    public function getEstadoCivil(): ?string { return $this->estadoCivil; }
    public function setEstadoCivil(?string $estadoCivil): static { $this->estadoCivil = $estadoCivil; return $this; }

    public function getGenero(): ?string { return $this->genero; }
    public function setGenero(?string $genero): static { $this->genero = $genero; return $this; }

    public function getFechaCiudadNacimiento(): ?string { return $this->fechaCiudadNacimiento; }
    public function setFechaCiudadNacimiento(?string $fechaCiudadNacimiento): static { $this->fechaCiudadNacimiento = $fechaCiudadNacimiento; return $this; }

    public function getFechaCiudadExpedicion(): ?string { return $this->fechaCiudadExpedicion; }
    public function setFechaCiudadExpedicion(?string $fechaCiudadExpedicion): static { $this->fechaCiudadExpedicion = $fechaCiudadExpedicion; return $this; }

    public function getCelular(): ?string { return $this->celular; }
    public function setCelular(string $celular): static { $this->celular = $celular; return $this; }

    public function getCorreo(): ?string { return $this->correo; }
    public function setCorreo(string $correo): static { $this->correo = $correo; return $this; }

    public function getDireccionDomicilio(): ?string { return $this->direccionDomicilio; }
    public function setDireccionDomicilio(?string $direccionDomicilio): static { $this->direccionDomicilio = $direccionDomicilio; return $this; }

    public function getDireccionLaboral(): ?string { return $this->direccionLaboral; }
    public function setDireccionLaboral(?string $direccionLaboral): static { $this->direccionLaboral = $direccionLaboral; return $this; }

    public function getCiudadTrabajoDomicilio(): ?string { return $this->ciudadTrabajoDomicilio; }
    public function setCiudadTrabajoDomicilio(?string $ciudadTrabajoDomicilio): static { $this->ciudadTrabajoDomicilio = $ciudadTrabajoDomicilio; return $this; }

    public function getNivelEstudios(): ?string { return $this->nivelEstudios; }
    public function setNivelEstudios(?string $nivelEstudios): static { $this->nivelEstudios = $nivelEstudios; return $this; }

    public function getProfesion(): ?string { return $this->profesion; }
    public function setProfesion(?string $profesion): static { $this->profesion = $profesion; return $this; }

    public function getEmpresaDondeLabora(): ?string { return $this->empresaDondeLabora; }
    public function setEmpresaDondeLabora(?string $empresaDondeLabora): static { $this->empresaDondeLabora = $empresaDondeLabora; return $this; }

    public function getCargo(): ?string { return $this->cargo; }
    public function setCargo(?string $cargo): static { $this->cargo = $cargo; return $this; }

    public function getTipoContrato(): ?string { return $this->tipoContrato; }
    public function setTipoContrato(?string $tipoContrato): static { $this->tipoContrato = $tipoContrato; return $this; }

    public function getFechaIngreso(): ?string { return $this->fechaIngreso; }
    public function setFechaIngreso(?string $fechaIngreso): static { $this->fechaIngreso = $fechaIngreso; return $this; }

    public function getPersonasCargo(): ?int { return $this->personasCargo; }
    public function setPersonasCargo(?int $personasCargo): static { $this->personasCargo = $personasCargo; return $this; }

    public function getIngresosFijos(): ?float { return $this->ingresosFijos; }
    public function setIngresosFijos(?float $ingresosFijos): static { $this->ingresosFijos = $ingresosFijos; return $this; }

    public function getIngresosVariables(): ?float { return $this->ingresosVariables; }
    public function setIngresosVariables(?float $ingresosVariables): static { $this->ingresosVariables = $ingresosVariables; return $this; }

    public function getPatrimonioInmuebles(): ?float { return $this->patrimonioInmuebles; }
    public function setPatrimonioInmuebles(?float $patrimonioInmuebles): static { $this->patrimonioInmuebles = $patrimonioInmuebles; return $this; }

    public function getValorVehiculos(): ?float { return $this->valorVehiculos; }
    public function setValorVehiculos(?float $valorVehiculos): static { $this->valorVehiculos = $valorVehiculos; return $this; }

    public function getDeudasFinancieras(): ?float { return $this->deudasFinancieras; }
    public function setDeudasFinancieras(?float $deudasFinancieras): static { $this->deudasFinancieras = $deudasFinancieras; return $this; }

    public function getGastosFinancieros(): ?float { return $this->gastosFinancieros; }
    public function setGastosFinancieros(?float $gastosFinancieros): static { $this->gastosFinancieros = $gastosFinancieros; return $this; }

    public function getDescripcionPerfil(): ?string { return $this->descripcionPerfil; }
    public function setDescripcionPerfil(?string $descripcionPerfil): static { $this->descripcionPerfil = $descripcionPerfil; return $this; }

    public function getValorInmueble(): ?float { return $this->valorInmueble; }
    public function setValorInmueble(?float $valorInmueble): static { $this->valorInmueble = $valorInmueble; return $this; }

    public function getValorSolicitado(): ?float { return $this->valorSolicitado; }
    public function setValorSolicitado(?float $valorSolicitado): static { $this->valorSolicitado = $valorSolicitado; return $this; }

    public function getSistemaAmortizacion(): ?string { return $this->sistemaAmortizacion; }
    public function setSistemaAmortizacion(?string $sistemaAmortizacion): static { $this->sistemaAmortizacion = $sistemaAmortizacion; return $this; }

    public function getPlazoAnos(): ?int { return $this->plazoAnos; }
    public function setPlazoAnos(?int $plazoAnos): static { $this->plazoAnos = $plazoAnos; return $this; }

    public function getLineaCredito(): ?string { return $this->lineaCredito; }
    public function setLineaCredito(?string $lineaCredito): static { $this->lineaCredito = $lineaCredito; return $this; }

    public function getTipoVivienda(): ?string { return $this->tipoVivienda; }
    public function setTipoVivienda(?string $tipoVivienda): static { $this->tipoVivienda = $tipoVivienda; return $this; }

    public function getCiudadInmueble(): ?string { return $this->ciudadInmueble; }
    public function setCiudadInmueble(?string $ciudadInmueble): static { $this->ciudadInmueble = $ciudadInmueble; return $this; }

    public function getReferenciaFamiliar(): ?string { return $this->referenciaFamiliar; }
    public function setReferenciaFamiliar(?string $referenciaFamiliar): static { $this->referenciaFamiliar = $referenciaFamiliar; return $this; }

    public function getReferenciaPersonal(): ?string { return $this->referenciaPersonal; }
    public function setReferenciaPersonal(?string $referenciaPersonal): static { $this->referenciaPersonal = $referenciaPersonal; return $this; }

    public function getAceptaTratamientoDatos(): ?bool { return $this->aceptaTratamientoDatos; }
    public function setAceptaTratamientoDatos(bool $aceptaTratamientoDatos): static { $this->aceptaTratamientoDatos = $aceptaTratamientoDatos; return $this; }

    public function getFechaSolicitud(): ?\DateTimeInterface { return $this->fechaSolicitud; }
    public function setFechaSolicitud(\DateTimeInterface $fechaSolicitud): static { $this->fechaSolicitud = $fechaSolicitud; return $this; }
}