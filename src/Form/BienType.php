<?php

namespace App\Form;

use App\Entity\Bien;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typeDeBien', ChoiceType::class, [
                'label' => 'Tipo de bien',
                'choices' => [
                    'Apartamento' => 'Appartement',
                    'Casa' => 'Maison',
                ],
                'required' => true,
            ])
            ->add('ville', TextType::class, [
                'label' => 'Ciudad',
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Dirección',
            ])
            ->add('prix', MoneyType::class, [
                'label' => 'Precio (COP)',
                'currency' => 'COP',
                'divisor' => 1,
                'scale' => 0,
            ])
            ->add('surfaceM2', IntegerType::class, [
                'label' => 'Superficie (m²)',
            ])
            ->add('etatDuBien', ChoiceType::class, [
                'label' => 'Estado',
                'choices' => [
                    'Nuevo' => 'Neuf',
                    'Antiguo' => 'Ancien',
                    'Renovado' => 'Rénové',
                ],
            ])
            ->add('tipoTransaccion', ChoiceType::class, [
                'label' => 'Transacción',
                'choices' => [
                    'Venta' => 'venta',
                    'Arriendo' => 'arriendo',
                    'Vacacional' => 'vacacional',
                ],
            ])
            ->add('foto', TextType::class, [
                'label' => 'Ruta de la foto (ej: assets/img/casas/casa1.jpg)',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bien::class,
        ]);
    }
}
