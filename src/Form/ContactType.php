<?php

namespace App\Form;

use App\Entity\ContactMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\IsTrue;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Tu nombre',
                'constraints' => [new NotBlank(['message' => 'Por favor escribe tu nombre.'])],
            ])
            ->add('phone', TextType::class, [
                'label' => 'Teléfono',
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Correo electrónico',
                'constraints' => [new NotBlank(['message' => 'Por favor escribe tu correo.'])],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Mensaje',
                'constraints' => [new NotBlank(['message' => 'Escribe tu mensaje.'])],
            ])
            ->add('aceptaTerminos', CheckboxType::class, [
                'label' => 'Acepto el tratamiento de mis datos conforme a la política de privacidad',
                'mapped' => false,
                'constraints' => [
                    new IsTrue(['message' => 'Debes aceptar los términos de privacidad.']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactMessage::class, // Conexión a entidad ContactMessage
        ]);
    }
}
