<?php

namespace App\Form;

use App\Entity\EstudioCliente;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class EstudioClienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Personales
            ->add('cedula', TextType::class, ['label' => 'Cédula de Ciudadanía', 'attr' => ['class' => 'form-control']])
            ->add('nombresApellidos', TextType::class, ['label' => 'Nombres y Apellidos', 'attr' => ['class' => 'form-control']])
            ->add('estadoCivil', TextType::class, ['required' => false, 'attr' => ['class' => 'form-control']])
            ->add('genero', ChoiceType::class, ['choices' => ['Masculino' => 'M', 'Femenino' => 'F', 'Otro' => 'O'], 'required' => false, 'attr' => ['class' => 'form-select']])
            ->add('fechaCiudadNacimiento', TextType::class, ['label' => 'Fecha y Ciudad de Nacimiento', 'required' => false, 'attr' => ['class' => 'form-control']])
            ->add('fechaCiudadExpedicion', TextType::class, ['label' => 'Fecha y Ciudad de Expedición C.C.', 'required' => false, 'attr' => ['class' => 'form-control']])
            ->add('celular', TextType::class, ['attr' => ['class' => 'form-control']])
            ->add('correo', EmailType::class, ['attr' => ['class' => 'form-control']])
            ->add('direccionDomicilio', TextType::class, ['label' => 'Dirección Domicilio y Teléfono', 'required' => false, 'attr' => ['class' => 'form-control']])
            ->add('direccionLaboral', TextType::class, ['label' => 'Dirección Laboral y Teléfono', 'required' => false, 'attr' => ['class' => 'form-control']])
            ->add('ciudadTrabajoDomicilio', TextType::class, ['label' => 'Ciudad de Trabajo y Domicilio', 'required' => false, 'attr' => ['class' => 'form-control']])
            
            // Laborales
            ->add('nivelEstudios', TextType::class, ['required' => false, 'attr' => ['class' => 'form-control']])
            ->add('profesion', TextType::class, ['required' => false, 'attr' => ['class' => 'form-control']])
            ->add('empresaDondeLabora', TextType::class, ['label' => 'Empresa o Actividad', 'required' => false, 'attr' => ['class' => 'form-control']])
            ->add('cargo', TextType::class, ['required' => false, 'attr' => ['class' => 'form-control']])
            ->add('tipoContrato', TextType::class, ['required' => false, 'attr' => ['class' => 'form-control']])
            ->add('fechaIngreso', TextType::class, ['required' => false, 'attr' => ['class' => 'form-control']])
            ->add('personasCargo', NumberType::class, ['required' => false, 'attr' => ['class' => 'form-control']])
            
            // Financieros
            ->add('ingresosFijos', NumberType::class, ['required' => false, 'attr' => ['class' => 'form-control']])
            ->add('ingresosVariables', NumberType::class, ['required' => false, 'attr' => ['class' => 'form-control']])
            ->add('patrimonioInmuebles', NumberType::class, ['label' => 'Patrimonio en Inmuebles', 'required' => false, 'attr' => ['class' => 'form-control']])
            ->add('valorVehiculos', NumberType::class, ['label' => 'Valor de Vehículos', 'required' => false, 'attr' => ['class' => 'form-control']])
            ->add('deudasFinancieras', NumberType::class, ['required' => false, 'attr' => ['class' => 'form-control']])
            ->add('gastosFinancieros', NumberType::class, ['required' => false, 'attr' => ['class' => 'form-control']])
            ->add('descripcionPerfil', TextareaType::class, ['label' => 'Descripción Perfil del Cliente', 'required' => false, 'attr' => ['class' => 'form-control']])
            
            // Crédito
            ->add('valorInmueble', NumberType::class, ['required' => false, 'attr' => ['class' => 'form-control']])
            ->add('valorSolicitado', NumberType::class, ['required' => false, 'attr' => ['class' => 'form-control']])
            ->add('sistemaAmortizacion', ChoiceType::class, ['choices' => ['Pesos' => 'Pesos', 'UVR' => 'UVR'], 'required' => false, 'attr' => ['class' => 'form-select']])
            ->add('plazoAnos', NumberType::class, ['label' => 'Plazo en Años', 'required' => false, 'attr' => ['class' => 'form-control']])
            ->add('lineaCredito', ChoiceType::class, ['choices' => ['Hipotecario' => 'Hipotecario', 'Leasing Habitacional' => 'Leasing'], 'required' => false, 'attr' => ['class' => 'form-select']])
            ->add('tipoVivienda', ChoiceType::class, ['choices' => ['Nueva' => 'Nueva', 'Usada' => 'Usada'], 'required' => false, 'attr' => ['class' => 'form-select']])
            ->add('ciudadInmueble', TextType::class, ['required' => false, 'attr' => ['class' => 'form-control']])
            
            // Referencias
            ->add('referenciaFamiliar', TextType::class, ['label' => 'Referencia Familiar (Nombre y Teléfono)', 'required' => false, 'attr' => ['class' => 'form-control']])
            ->add('referenciaPersonal', TextType::class, ['label' => 'Referencia Personal (Nombre y Teléfono)', 'required' => false, 'attr' => ['class' => 'form-control']])
            
            // HABEAS DATA
            ->add('aceptaTratamientoDatos', CheckboxType::class, [
                'label' => 'Autorizo el tratamiento de mis datos personales según la Ley 1581 de 2012 (Habeas Data) en Colombia.',
                'mapped' => true,
                'constraints' => [
                    new IsTrue(['message' => 'Debe aceptar los términos de protección de datos para continuar.'])
                ],
                'attr' => ['class' => 'form-check-input']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => EstudioCliente::class]);
    }
}