<?php

namespace App\Form;

use App\Entity\Visite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateVisite', DateType::class, [
                'label' => 'Fecha de la Visita',
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control shadow-sm', 'min' => (new \DateTime())->format('Y-m-d')]
            ])
            ->add('commentaires', TextareaType::class, [
                'label' => 'Comentarios o disponibilidad de horario',
                'required' => false,
                'attr' => ['class' => 'form-control shadow-sm', 'rows' => 3, 'placeholder' => 'Ej: Prefiero en la mañana...']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Visite::class,
        ]);
    }
}