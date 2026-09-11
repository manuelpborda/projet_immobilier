<?php

namespace App\Form;

use App\Entity\Testimonio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestimonioForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombreCliente', TextType::class, [
                'label' => 'Nombre del Cliente',
                'attr' => ['placeholder' => 'Ej. Familia Pérez / Carlos Mendoza']
            ])
            ->add('rolCliente', ChoiceType::class, [
                'label' => 'Rol / Tipo de Operación',
                'choices' => [
                    'Comprador de Inmueble' => 'Comprador Satisfecho',
                    'Propietario (Venta)' => 'Propietario Vendedor',
                    'Propietario (Arriendo)' => 'Propietario Inversionista',
                    'Arrendatario / Inquilino' => 'Arrendatario',
                    'Turista (Alquiler Vacacional)' => 'Huésped Vacacional',
                ],
                'placeholder' => '-- Selecciona el rol del cliente --',
                'required' => true,
            ])
            ->add('mensaje', TextareaType::class, [
                'label' => 'Comentario del Cliente',
                'attr' => ['rows' => 4, 'placeholder' => 'Escribe aquí lo que dijo el cliente sobre el servicio...']
            ])
            ->add('calificacion', ChoiceType::class, [
                'label' => 'Calificación (Estrellas)',
                'choices' => [
                    '⭐⭐⭐⭐⭐ (5 Estrellas)' => 5,
                    '⭐⭐⭐⭐ (4 Estrellas)' => 4,
                    '⭐⭐⭐ (3 Estrellas)' => 3,
                    '⭐⭐ (2 Estrellas)' => 2,
                    '⭐ (1 Estrella)' => 1,
                ],
            ])
            ->add('urlVideo', UrlType::class, [
                'label' => 'Link de Video en YouTube (Opcional)',
                'required' => false,
                'attr' => ['placeholder' => 'Ej. https://www.youtube.com/watch?v=...']
            ])
            ->add('activo', CheckboxType::class, [
                'label' => '¿Publicar y mostrar en la página principal?',
                'required' => false,
                'data' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Testimonio::class,
        ]);
    }
}