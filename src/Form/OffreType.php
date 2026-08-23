<?php

namespace App\Form;

use App\Entity\Offre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prix', NumberType::class, [
                'label' => 'Valor de tu Oferta (COP)',
                'attr' => ['class' => 'form-control shadow-sm', 'placeholder' => 'Ej: 250000000']
            ])
            ->add('conditionsAchat', TextareaType::class, [
                'label' => 'Condiciones de compra/arriendo',
                'required' => false,
                'attr' => ['class' => 'form-control shadow-sm', 'rows' => 3, 'placeholder' => 'Ej: Pago de contado, crédito preaprobado...']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}